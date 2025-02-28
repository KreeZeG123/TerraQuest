<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\OngoingChallenge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class GameController extends AbstractController
{
    private static $zone = [
        [
            'lat' => 47.500321,
            'lng' => -0.569403,
            'title' => "Instant d'Asie",
            'slug' => 'instant-asie'
        ],
        [
            'lat' => 47.500337,
            'lng' => -0.568607,
            'title' => "Les plantes carnivores",
            'slug' => 'plantes-carnivores'
        ],
        [
            'lat' => 47.499954,
            'lng' => -0.567926,
            'title' => "A l'épreuve des extremes",
            'slug' => 'epreuve-des-extremes'
        ],
        [
            'lat' => 47.501177,
            'lng' => -0.569753,
            'title' => "Le jardin sans eau",
            'slug' => 'jardin-sans-eau'
        ],
        [
            'lat' => 47.501504,
            'lng' => -0.568679,
            'title' => "Les racines de la vie",
            'slug' => 'racines-de-la-vie'
        ],
        [
            'lat' => 47.501662,
            'lng' => -0.569141,
            'title' => "Bain de couleurs",
            'slug' => 'bain-de-couleurs'
        ],
        [
            'lat' => 47.502219,
            'lng' => -0.569910,
            'title' => "La Roseraie",
            'slug' => 'roseraie'
        ],
        [
            'lat' => 47.501974,
            'lng' => -0.571391,
            'title' => "L'allée des grands-mères",
            'slug' => 'allee-grands-meres'
        ],
        [
            'lat' => 47.501762,
            'lng' => -0.572003,
            'title' => "Les Bayous de Louisiane",
            'slug' => 'bayous-louisiane'
        ],
        [
            'lat' => 47.500925,
            'lng' => -0.572340,
            'title' => "Le Trésor de la Pérouse",
            'slug' => 'tresor-perouse'
        ],
        [
            'lat' => 47.500479,
            'lng' => -0.571537,
            'title' => "La serre au papillons",
            'slug' => 'serre-papillons'
        ]
    ];

    #[Route('/parties', name: 'games')]
    public function games(): Response
    {
        return $this->render('game/games.html.twig');
    }

    #[Route('/selectionner-zone', name: 'select_area')]
    public function select_area(): Response
    {

        $hotspots = self::$zone;

        return $this->render('game/select_area.html.twig', [
            'hotspots' => $hotspots
        ]);
    }

    #[Route('/rejoindre-zone/{slug}/{id?}', name: 'reach_area', requirements: ['slug' => Requirement::ASCII_SLUG, 'id' => Requirement::DIGITS])]
    public function reach_area(string $slug, ?string $id = ""): Response
    {
        $latDep = 47.500190679765005;
        $lngDep = -0.5706363950740977;

        $res = array_filter(self::$zone, function($z) use ($slug) {
            return $z['slug'] === $slug;
        });
        $r = $res[array_key_first($res)];
        $latDest = $r['lat'];
        $lngDest = $r['lng'];

        if (!$id || $id === "") {
            # Créer une nouvelle partie
            $id = "0";
        }

        return $this->render('game/reach_area.html.twig', [
            'latDep' => $latDep,
            'lngDep' => $lngDep,
            'latDest' => $latDest,
            'lngDest' => $lngDest,
            'gameID' => $id
        ]);
    }

    #[Route('/play/{gameID}/', name: 'play', requirements: ['gameID' => Requirement::DIGITS])]
    public function play(int $gameID)
    {
        $game = [
            'areasCompleted' => 0,
            'numberOfAreas' => 11,
            'userId' => ""
        ];

        $challenge = (new Challenge())
            ->setType("text")
            ->setDescription("Ah, cette plante carnivore… Je me souviens qu’elle a un mécanisme vraiment fascinant. Malheureusement, mes notes sont incomplètes ! Explore cette zone et utilise ton scanner pour m’aider.")
            ->setImage("images/avatar-50x50.png");

        $ongoingChallenge = (new OngoingChallenge())
            ->setChallenge($challenge)
            ->setLastHint("Aucun Indice")
            ->setLastScannedSpecies("Aucune Espèce Scannée");


        return $this->render( 'game/play.html.twig', [
            'game' => $game,
            'ongoingChallenge' => $ongoingChallenge,
            'gameID' => $gameID
        ]);
    }

    #[Route('/play/{gameID}/map', name: 'play.map', requirements: ['gameID' => Requirement::DIGITS])]
    public function map(int $gameID)
    {

        $zone = [
            'latGPS' => 47.501177,
            'lngGPS' => -0.569753,
        ];

        $hotspots = [
            [
                'lat' => 47.50131,
                'lng' => -0.56985,
                'title' => "Espèce n°1",
                'slug' => ''
            ],
            [
                'lat' => 47.50131,
                'lng' => -0.56957,
                'title' => "Espèce n°2",
                'slug' => ''
            ],
            [
                'lat' => 47.50116,
                'lng' => -0.56984,
                'title' => "Espèce n°3",
                'slug' => ''
            ],
            [
                'lat' => 47.50101,
                'lng' => -0.56974,
                'title' => "Espèce n°4",
                'slug' => ''
            ],
        ];

        return $this->render('game/map.html.twig', [
            'hotspots' => $hotspots,
            'zone' => $zone,
            'gameID' => $gameID,
        ]);

    }

}
