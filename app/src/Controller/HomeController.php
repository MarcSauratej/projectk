<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Manga;
use App\Entity\Anime;
use App\Entity\Battles;
use App\Entity\Breeds;
use App\Entity\Characters;
use App\Entity\Movies;
use App\Entity\Sagas;
use App\Entity\Specials;
use App\Entity\Quiz;
use App\Entity\Reward;
use App\Entity\UserQuiz;
use App\Entity\UserReward;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController {

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $doctrine)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response {

        $repository = $this->doctrine->getRepository(Quiz::class);
        $quizzes = $repository->findAll();

        return $this->render('home/home.html.twig', [
            'quizzes' => $quizzes
        ]);
    }

    #[Route('/quiz/{id}', name: 'app_quiz_view')]
    public function quizView(Quiz $quiz, Request $request, int $id): Response {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_account');
        } else if (!$this->getUser()->isVerified()) {
            $this->addFlash('danger', 'Por favor, verifica tu cuenta.');

            return $this->redirectToRoute('app_account');
        }

        $repository = $this->doctrine->getRepository(Quiz::class);
        $quiz = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/quizView.html.twig', [
            'quiz' => $quiz
        ]);
    }

    #[Route('/quizResponse/{id}', name: 'app_quizResponse')]
    public function quizResponse(Quiz $quiz, Request $request, int $id, ManagerRegistry $doctrine): Response {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_account');
        } else if (!$this->getUser()->isVerified()) {
            $this->addFlash('danger', 'Por favor, verifica tu cuenta.');

            return $this->redirectToRoute('app_account');
        }

        $repository = $this->doctrine->getRepository(Quiz::class);
        $quiz = $repository->findOneBy([
            'id' => $id
        ]);

        $repositoryReward = $this->doctrine->getRepository(Reward::class);
        $reward = $repositoryReward->findOneBy([
            'id' => $id
        ]);

        $user = $this->getUser();
        $date = new \DateTime();

        $userReward = new UserReward();
        $userReward->setReward($reward);
        $userReward->setUser($user);
        $userReward->setAcquired(true);

        $userQuiz = new UserQuiz();
        $userQuiz->setUser($user);
        $userQuiz->setQuiz($quiz);
        $userQuiz->setCreatedAt($date);
        $userQuiz->setUpdatedAt($date);
        $userQuiz->setStatus("completed");

        $this->entityManager = $doctrine->getManager();
        $this->entityManager->persist($userReward);
        $this->entityManager->persist($userQuiz);
        $this->entityManager->flush();

        return $this->render('home/views/quizResponse.html.twig', [
            'quiz' => $quiz
        ]);
    }

    #[Route('/manga', name: 'app_manga')]
    public function manga(Request $request): Response {

        $repository = $this->doctrine->getRepository(Manga::class);
        $manga = $repository->findAll();

        return $this->render('home/manga.html.twig', [
            'mangas' => $manga
        ]);
    }

    #[Route('/manga/{id}', name: 'app_manga_view')]
    public function mangaView(Manga $manga, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Manga::class);
        $manga = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/mangaView.html.twig', [
            'mangas' => $manga
        ]);
    }

    #[Route('/anime', name: 'app_anime')]
    public function anime(Request $request): Response {

        $repository = $this->doctrine->getRepository(Anime::class);
        $anime = $repository->findAll();

        return $this->render('home/anime.html.twig', [
            'animes' => $anime
        ]);
    }

    #[Route('/anime/{id}', name: 'app_anime_view')]
    public function animeView(Anime $anime, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Anime::class);
        $anime = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/animeView.html.twig', [
            'animes' => $anime
        ]);
    }

    #[Route('/movies', name: 'app_movies')]
    public function movies(Request $request): Response {

        $repository = $this->doctrine->getRepository(Movies::class);
        $movies = $repository->findAll();

        return $this->render('home/movies.html.twig', [
            'movies' => $movies
        ]);
    }

    #[Route('/movies/{id}', name: 'app_movies_view')]
    public function moviesView(Movies $movies, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Movies::class);
        $movies = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/moviesView.html.twig', [
            'movies' => $movies
        ]);
    }

    #[Route('/specials', name: 'app_specials')]
    public function specials(Request $request): Response {

        $repository = $this->doctrine->getRepository(Specials::class);
        $specials = $repository->findAll();

        return $this->render('home/specials.html.twig', [
            'specials' => $specials
        ]);
    }

    #[Route('/specials/{id}', name: 'app_specials_view')]
    public function specialsView(Specials $specials, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Specials::class);
        $specials = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/specialsView.html.twig', [
            'specials' => $specials
        ]);
    }

    #[Route('/sagas', name: 'app_sagas')]
    public function sagas(Request $request): Response {

        $repository = $this->doctrine->getRepository(Sagas::class);
        $sagas = $repository->findAll();

        return $this->render('home/sagas.html.twig', [
            'sagas' => $sagas
        ]);
    }

    #[Route('/sagas/{id}', name: 'app_sagas_view')]
    public function sagasView(Sagas $sagas, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Sagas::class);
        $sagas = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/sagasView.html.twig', [
            'sagas' => $sagas
        ]);
    }

    #[Route('/battles', name: 'app_battles')]
    public function battles(Request $request): Response {

        $repository = $this->doctrine->getRepository(Battles::class);
        $battles = $repository->findAll();

        return $this->render('home/battles.html.twig', [
            'battles' => $battles
        ]);
    }

    #[Route('/battles/{id}', name: 'app_battles_view')]
    public function battlesView(Battles $battles, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Battles::class);
        $battles = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/battlesView.html.twig', [
            'battles' => $battles
        ]);
    }

    #[Route('/breeds', name: 'app_breeds')]
    public function breeds(Request $request): Response {

        $repository = $this->doctrine->getRepository(Breeds::class);
        $breeds = $repository->findAll();

        return $this->render('home/breeds.html.twig', [
            'breeds' => $breeds
        ]);
    }

    #[Route('/breeds/{id}', name: 'app_breeds_view')]
    public function breedsView(Breeds $breeds, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Breeds::class);
        $breeds = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/breedsView.html.twig', [
            'breeds' => $breeds
        ]);
    }

    #[Route('/characters', name: 'app_characters')]
    public function characters(Request $request): Response {

        $repository = $this->doctrine->getRepository(Characters::class);
        $characters = $repository->findAll();

        return $this->render('home/characters.html.twig', [
            'characters' => $characters
        ]);
    }

    #[Route('/characters/{id}', name: 'app_characters_view')]
    public function charactersView(Characters $characters, Request $request, int $id): Response {

        $repository = $this->doctrine->getRepository(Characters::class);
        $characters = $repository->findOneBy([
            'id' => $id
        ]);

        return $this->render('home/views/charactersView.html.twig', [
            'characters' => $characters
        ]);
    }

    #[Route('/rewards', name: 'app_rewards')]
    public function rewards(): Response {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        $repositoryR = $this->doctrine->getRepository(Reward::class);
        $rewards = $repositoryR->findAll();

        $array = array();
        foreach($rewards as $reward){
            $array[] = array(
                'reward' => $reward,
                'userReward' => $this->getUser()->isRewarded($reward)
            );
        }

        return $this->render('home/rewards.html.twig', [
            'rewards' => $array
        ]);
    }
}