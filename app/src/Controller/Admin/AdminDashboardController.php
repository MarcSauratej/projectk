<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Quiz;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Doctrine\Persistence\ManagerRegistry;

class AdminDashboardController extends AbstractDashboardController
{

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setFaviconPath('/img/pklogo.svg')
            ->renderContentMaximized()
            ->renderSidebarMinimized();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Quizzes'),
            MenuItem::linkToCrud('Quizzes', 'fa fa-file-text', Quiz::class)
            ->setController(QuizCrudController::class),

            MenuItem::linkToCrud('Add Quiz', 'fa fa-tags', Quiz::class)
            ->setAction('new'),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class)
            ->setController(UserCrudController::class),

            MenuItem::linkToCrud('Add User', 'fa fa-tags', User::class)
            ->setAction('new'),

            MenuItem::section('Access'),
            MenuItem::linkToLogout('Logout', 'fa fa-exit')
        ];
    }

    /*public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)

            ->setName($user->getUsername())
            ->displayUserName(false)
            ->setAvatarUrl($user->getAvatar())
            ->displayUserAvatar(false)

            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card'),
                MenuItem::linkToRoute('Settings', 'fa fa-user-cog'),
                MenuItem::section(),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out')
            ]);
    }*/

    public function configureAssets(): Assets
    {
        return Assets::new()->addWebpackEncoreEntry('app');
    }
}
