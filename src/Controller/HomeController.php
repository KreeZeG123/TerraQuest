<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/explication', name: 'explanation')]
    #[Route('/jeu/{gameID}/explication', name: 'play.explanation', requirements: ['gameID' => '\d+'])]
    public function explanation(?int $gameID = null): Response
    {
        return $this->render('home/explanation.html.twig', [
            'gameID' => $gameID
        ]);
    }

}
