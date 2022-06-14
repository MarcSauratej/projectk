<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Security\EmailVerifier;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SettingsFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\UserReward;

class AccountController extends AbstractController {

    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $entityManager, ManagerRegistry $doctrine)
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
    }

    #[Route('/profile', name: 'app_account')]
    public function index(): Response {
        $user = $this->getUser();

        if (!$user->isVerified()) {
            return $this->redirectToRoute('app_account');
        }

       return $this->render('account/profile.html.twig', [
        'user' => $user
       ]);
    }

    #[Route('/profile', name: 'app_account')]
    public function settings(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine): Response {
        $user = $this->getUser();

        $form = $this->createForm(SettingsFormType::class, $user);
        $form->handleRequest($request);

        $date = new \DateTime();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername($form->get('username')->getData());
            $user->setUpdatedAt($date);
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move(
                        $this->getParameter('avatars'),
                        $newFilename
                    );
                    $user->setAvatar($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'No se ha podido actualizar tu perfil, por favor revisa los datos introducidos.');
                }
            }

            $this->entityManager = $doctrine->getManager();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Tu cuenta ha sido actualizada.');
        
        }

        $user = $this->getUser();

        $repositoryU = $this->doctrine->getRepository(UserReward::class);
        $userRewards = $repositoryU->findBy([
            'user' => $user = $this->getUser(),
        ]);
        
        return $this->render('account/profile.html.twig', [
            'settingsForm' => $form->createView(),
            'user' => $user,
            'userRewards' => $userRewards
        ]);
    }

    #[Route('/profile/emailVerify', name: 'app_account_emailVerify')]
    public function emailVerify(Request $request, EmailVerifier $emailVerifier, TokenStorageInterface $tokenStorage, MailerInterface $mailer): Response {
        $user = $this->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('success', 'Verificación de Email Enviada');
        }

        $emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('mailer@kakarot.squaremx.com', 'Project Kakarot'))
                ->to($user->getEmail())
                ->subject('Por favor, confirma tu Email.')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        return $this->redirectToRoute('app_account');
    }

    #[Route('/profile/delete', name: 'app_account_delete')]
    public function deleteAccount(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer, TokenStorageInterface $tokenStorage): Response {
        $user = $this->getUser();

        $user->setDeactivated(true);
        $this->entityManager = $doctrine->getManager();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $email = null;
        $email = (new TemplatedEmail())
            ->from(new Address('mailer@kakarot.squaremx.com', 'Project Kakarot'))
            ->to($user->getEmail())
            ->subject('Eliminación de Cuenta')
            ->htmlTemplate('emails/deleteAccount_email.html.twig');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Error enviando el correo.');
        }
    
        $request->getSession()->invalidate();
        $tokenStorage->setToken(null);
        return $this->redirectToRoute('app_account');
    }

}