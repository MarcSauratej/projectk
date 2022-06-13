<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Manga;
use App\Entity\Anime;
use App\Entity\Battles;
use App\Entity\Breeds;
use App\Entity\Characters;
use App\Entity\Movies;
use App\Entity\Specials;
use App\Entity\Sagas;
use App\Entity\Question;
use App\Entity\Answer;
use App\Entity\Reward;

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
            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class)
            ->setController(UserCrudController::class),

            MenuItem::section('Quizzes'),
            MenuItem::linkToCrud('Quizzes', 'fa fa-file-text', Quiz::class)
            ->setController(QuizCrudController::class),

            MenuItem::linkToCrud('Add Quiz', 'fa fa-tags', Quiz::class)
            ->setAction('new'),

            MenuItem::section('Questions'),
            MenuItem::linkToCrud('Questions', 'fa fa-file-text', Question::class)
            ->setController(QuestionCrudController::class),

            MenuItem::linkToCrud('Add Question', 'fa fa-tags', Question::class)
            ->setAction('new'),

            MenuItem::section('Answers'),
            MenuItem::linkToCrud('Answers', 'fa fa-file-text', Answer::class)
            ->setController(AnswerCrudController::class),

            MenuItem::linkToCrud('Add Answer', 'fa fa-tags', Answer::class)
            ->setAction('new'),

            MenuItem::section('Rewards'),
            MenuItem::linkToCrud('Rewards', 'fa fa-file-text', Reward::class)
            ->setController(RewardCrudController::class),

            MenuItem::linkToCrud('Add Reward', 'fa fa-tags', Reward::class)
            ->setAction('new'),

            MenuItem::section('Manga'),
            MenuItem::linkToCrud('Manga', 'fa fa-file-text', Manga::class)
            ->setController(MangaCrudController::class),

            MenuItem::linkToCrud('Add Manga', 'fa fa-tags', Manga::class)
            ->setAction('new'),

            MenuItem::section('Anime'),
            MenuItem::linkToCrud('Anime', 'fa fa-file-text', ManAnimega::class)
            ->setController(AnimeCrudController::class),

            MenuItem::linkToCrud('Add Anime', 'fa fa-tags', Anime::class)
            ->setAction('new'),

            MenuItem::section('Movies'),
            MenuItem::linkToCrud('Movies', 'fa fa-file-text', Movies::class)
            ->setController(MoviesCrudController::class),

            MenuItem::linkToCrud('Add Movies', 'fa fa-tags', Movies::class)
            ->setAction('new'),

            MenuItem::section('Specials'),
            MenuItem::linkToCrud('Specials', 'fa fa-file-text', Specials::class)
            ->setController(SpecialsCrudController::class),

            MenuItem::linkToCrud('Add Specials', 'fa fa-tags', Specials::class)
            ->setAction('new'),

            MenuItem::section('Sagas'),
            MenuItem::linkToCrud('Sagas', 'fa fa-file-text', Sagas::class)
            ->setController(SagasCrudController::class),

            MenuItem::linkToCrud('Add Sagas', 'fa fa-tags', Sagas::class)
            ->setAction('new'),

            MenuItem::section('Battles'),
            MenuItem::linkToCrud('Battles', 'fa fa-file-text', Battles::class)
            ->setController(BattlesCrudController::class),

            MenuItem::linkToCrud('Add Battles', 'fa fa-tags', Battles::class)
            ->setAction('new'),

            MenuItem::section('Breeds'),
            MenuItem::linkToCrud('Breeds', 'fa fa-file-text', Breeds::class)
            ->setController(BreedsCrudController::class),

            MenuItem::linkToCrud('Add Breeds', 'fa fa-tags', Breeds::class)
            ->setAction('new'),

            MenuItem::section('Characters'),
            MenuItem::linkToCrud('Characters', 'fa fa-file-text', Characters::class)
            ->setController(CharactersCrudController::class),

            MenuItem::linkToCrud('Add Characters', 'fa fa-tags', Characters::class)
            ->setAction('new')
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
