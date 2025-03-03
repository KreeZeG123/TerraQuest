<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\OngoingChallenge;
use App\Entity\Quiz;
use App\Entity\Species;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class GameController extends AbstractController
{
    /**
     * ======
     * TODO : REMPLACER CODE TEMPORAIRE AU DESSOUS
     * ======
     */

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

    private static $allSpecies;

    public static function getAllSpecies(): array {
        if (self::$allSpecies === null) {
            self::$allSpecies = [
                (new Species())
                    ->setId(0)
                    ->setLatinName("Dionaea muscipula")
                    ->setSlug('dionaea-muscipula')
                    ->setCommonName("Dionée attrape-mouche")
                    ->setOrigin("Endémique des zones marécageuses de la Caroline du Nord et du Sud aux États-Unis, où elle pousse dans des sols sableux pauvres soumis à une forte humidité et ensoleillement.")
                    ->setCharacteristics("Pièges actifs en forme de mâchoire qui se referment sur les insectes.\nPossède des poils sensitifs déclenchant la fermeture du piège.\nSécrète des enzymes pour digérer les proies.")
                    ->setUtility("Régule naturellement les populations de petits insectes volants dans son environnement.")
                    ->setCultivationCondition("Substrat : Tourbe blonde pure ou mélangée à de la perlite.\nLumière : Soleil direct ou lumière vive.\nHumidité : Substrat toujours humide avec de l’eau déminéralisée.")
                    ->setImages(['images/prof-removebg-reverse.png']),
            ];
        }
        return self::$allSpecies;
    }

    public function __construct()
    {
        self::getAllSpecies();
    }

    /**
     * ======
     * TODO : REMPLACER CODE TEMPORAIRE AU DESSUS
     * ======
     */

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

    #[Route('/jeu/{gameID}/', name: 'play', requirements: ['gameID' => Requirement::DIGITS])]
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

    #[Route('/jeu/{gameID}/map', name: 'play.map', requirements: ['gameID' => Requirement::DIGITS])]
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

    #[Route('/jeu/{gameID}/scanner', name: 'play.scanner', requirements: ['gameID' => Requirement::DIGITS])]
    public function scanner(int $gameID)
    {
        return $this->render('game/scanner.html.twig', [
            'gameID' => $gameID
        ]);
    }

    #[Route('jeu/{gameID}/informations-espece/{speciesID}', name: 'play.species_information', requirements: ['gameID' => Requirement::DIGITS, 'speciesID' => '\d+|__SPECIESID__'])]
    public function species_information(int $gameID, int $speciesID)
    {
        $species = self::getAllSpecies()[$speciesID];

        return $this->render('game/species_information.html.twig', [
            'gameID' => $gameID,
            'species' => $species
        ]);
    }

    #[Route('/jeu/{gameID}/reponse-vraie', name: 'play.correct_guess', requirements: ['gameID' => Requirement::DIGITS])]
    public function correct_guess(int $gameID) : Response
    {
        return $this->render('game/correct_guess.html.twig', [
            'gameID' => $gameID
        ]);
    }

    #[Route('/jeu/{gameID}/reponse-fausse', name: 'play.wrong_guess', requirements: ['gameID' => Requirement::DIGITS])]
    public function wrong_guess(int $gameID) : Response
    {
        return $this->render('game/wrong_guess.html.twig', [
            'gameID' => $gameID
        ]);
    }

    #[Route('/jeu/{gameID}/indices', name: 'play.hints', requirements: ['gameID' => Requirement::DIGITS])]
    public function hints(int $gameID) : Response
    {
        $hints = [
            "Elle se nourrit d’insectes comme les autres ici, mais son piège est unique. Il ne ressemble ni à un tube ni à une surface collante.",
            "Elle utilise un mécanisme en deux parties qui se referme sur sa proie. Un piège redoutable !",
            "Son piège ne se déclenche qu’après plusieurs mouvements. Elle ne se laisse pas berner facilement.",
            "Je me souviens, elle est incroyablement rapide. Ses 'mâchoires' capturent ses proies en un éclair.",
            "Ça y est, son nom me revient : Dionaea muscipula, ou plus simplement, la Dionée attrape-mouche !"
        ];

        return $this->render('game/hints.html.twig', [
            'gameID' => $gameID,
            'hints' => $hints
        ]);
    }

    #[Route('/jeu/{gameID}/especes-scannees', name: 'play.scanned_species', requirements: ['gameID' => Requirement::DIGITS])]
    public function scanned_species(int $gameID) : Response
    {

        $species = self::getAllSpecies();

        $scanedSpeciesDTO = [
            [
                'latinName' => $species[0]->getLatinName(),
                'id' => $species[0]->getId()
            ],
            [
                'latinName' => "zdzzdzd  zdzd zd zd d",
                'id' => $species[0]->getId()
            ],
        ];

        return $this->render('game/scanned_species.html.twig', [
            'gameID' => $gameID,
            'scannedSpecies' => $scanedSpeciesDTO
        ]);
    }

    #[Route('/jeu/{gameID}/quiz', name: 'play.quiz', requirements: ['gameID' => Requirement::DIGITS])]
    public function quiz(int $gameID)
    {

        $quiz = (new Quiz())
            ->setSpecies(self::getAllSpecies()[0])
            ->setQuestion("Quel mécanisme unique la Dionée utilise-t-elle pour attraper ses proies ?")
            ->setAnswers([
                "Une substance collante",
                "Des pièges en forme d'urnes",
                "Des mâchoires sensibles qui se referment rapidement",
                "Une toile gluante"
            ])
            ->setCorrectAnswer("Des mâchoires sensibles qui se referment rapidement");

        $journeyEnding = false;

        return $this->render('game/quiz.html.twig', [
            'gameID' => $gameID,
            'quiz' => $quiz,
            'journeyEnding' => $journeyEnding
        ]);
    }

    #[Route('/jeu/{gameID}/fin-parcours',  name: 'play.journey_ending', requirements: ['gameID' => Requirement::DIGITS])]
    public function journey_ending(int $gameID): Response
    {
        return $this->render('game/journey_ending.html.twig', [
            'gameID' => $gameID,
        ]);
    }

}
