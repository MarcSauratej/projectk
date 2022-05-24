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

class AccountController extends AbstractController {

    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $entityManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername($form->get('username')->getData());
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
                    // ... handle exception if something happens during file upload
                }
            }
            $this->entityManager = $doctrine->getManager();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Tu cuenta ha sido actualizada');
        
        }

        $user = $this->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('error', 'Por favor verifica tu email');
        }

        return $this->render('account/profile.html.twig', [
            'settingsForm' => $form->createView(),
            'user' => $user
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
                ->from(new Address('mailer@projectkakarot.com', 'Project Kakarot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        return $this->redirectToRoute('app_account');
    }

    #[Route('/profile/delete', name: 'app_account_delete')]
    public function deleteAccount(Request $request): Response {
        $user = $this->getUser();

        if (!$user->isVerified()) {
            return $this->redirectToRoute('app_account');
        }

        $user->setDeactivated(true);
        $this->entityManager = $doctrine->getManager();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $email = null;
        $email = (new TemplatedEmail())
            ->from(new Address('mailer@projectkakarot.com', 'Project Kakarot'))
            ->to($user->getEmail())
            ->subject('Eliminación de Cuenta')
            ->htmlTemplate('email/deleteAccount.html.twig');

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