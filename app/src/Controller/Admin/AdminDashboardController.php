<?php

namespace App\Controller\Admin;

use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Doctrine\Persistence\ManagerRegistry;

class AdminDashboardController extends AbstractDashboardController
{
    private $chartBuilder;

    public function __construct(ChartBuilderInterface $chartBuilder, ManagerRegistry $doctrine)
    {
        $this->chartBuilder = $chartBuilder;
        $this->doctrine = $doctrine;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $usersRepo = $this->doctrine->getRepository(User::class);

        $totalUsers = $usersRepo->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        for ($i = 0; $i <= 6; $i++) {
            $labels[] = date("m-Y", strtotime(date( 'Y-m-01' )." -$i months"));
        }

        $userDatasets = [];

        $reversed = array_reverse($labels);

        foreach($reversed as $label){
            $userDate = new \DateTime("01-{$label}");
            $userDatasets[$label] = $usersRepo->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.createdAt >= :start')
            ->andWhere('u.createdAt <= :end')
            ->setParameter('start', $userDate->format('Y-m-d'))
            ->setParameter('end', $userDate->modify('first day of next month')->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();
        }
    
        $userChart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $userChart->setData([
            'labels' => $reversed,
            'datasets' => [
                [
                    'label' => 'Usuarios',
                    'tension' => 0.2,
                    'pointStyle'=> 'circle',
                    'pointRadius'=> 7,
                    'pointHoverRadius' => 15,
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $userDatasets,
                ],
            ],
        ]);

        return $this->render('admin/index.html.twig', [
            'totalUsers' => $totalUsers,
            'userChart' => $userChart
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Project Kakarot - Panel de Administración');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Quizzes'),
            MenuItem::linkToCrud('Quizzes', 'fa fa-file-text', Quiz::class)
            ->setController(QuizController::class),

            MenuItem::section('Usuarios'),
            MenuItem::linkToCrud('Usuarios', 'fa fa-user', User::class)
            ->setController(AccountController::class),

            MenuItem::section('Access'),
            MenuItem::linkToLogout('Logout', 'fa fa-exit')
        ];
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addWebpackEncoreEntry('app');
    }
}
