<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response {
        
        return $this->render('home/home.html.twig');
    }

    #[Route('/manga', name: 'app_manga')]
    public function manga(Request $request): Response {

        return $this->render('home/manga.html.twig');
    }

    #[Route('/anime', name: 'app_anime')]
    public function anime(Request $request): Response {

        return $this->render('home/anime.html.twig');
    }

    #[Route('/movies', name: 'app_movies')]
    public function movies(Request $request): Response {

        return $this->render('home/movies.html.twig');
    }

    #[Route('/specials', name: 'app_specials')]
    public function specials(Request $request): Response {

        return $this->render('home/specials.html.twig');
    }

    #[Route('/battles', name: 'app_battles')]
    public function battles(Request $request): Response {

        return $this->render('home/battles.html.twig');
    }

    #[Route('/characters', name: 'app_characters')]
    public function characters(Request $request): Response {

        return $this->render('home/characters.html.twig');
    }
}