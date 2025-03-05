<?php

namespace App\Controller;

use App\DTO\HotspotDTO;
use App\DTO\OngoingChallengeDTO;
use App\DTO\OngoingGameDTO;
use App\DTO\ScannedSpeciesDTO;
use App\Entity\Area;
use App\Entity\Challenge;
use App\Entity\Game;
use App\Entity\Journey;
use App\Entity\OngoingChallenge;
use App\Entity\Quiz;
use App\Entity\Species;
use App\Repository\AreaRepository;
use App\Repository\GameRepository;
use App\Repository\JourneyRepository;
use App\Repository\SpeciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use function Symfony\Component\String\s;
use function Symfony\Component\Translation\t;

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
    public function games(GameRepository $gameRepository): Response
    {

        $isConnected = false;
        $games = [];

        $user = $this->getUser();
        if ($user !== null) {
            $games = $gameRepository->findOngoingGames($user);
            $isConnected = true;
        }

        $ongoingGames = array_map(function (Game $g): OngoingGameDTO {
            $title = "Zone " . $g->getNumberOfAreasCompleted()+1 . "/" . $g->getNumberOfAreas() .
                " \"" .
                $g->getOngoingChallenge()->getChallenge()->getArea()->getTitle() .
                "\" - " .
                ($g->getUpdatedAt() ? $g->getUpdatedAt()->format("d/m/Y") : "Date inconnue")
            ;

            return new OngoingGameDTO(
                title: $title,
                gameID: $g->getId()
            );
        }, $games);

        return $this->render('game/games.html.twig', [
            "connected" => $isConnected,
            "games" => $ongoingGames
        ]);
    }

    #[Route('/selectionner-zone', name: 'select_area')]
    public function select_area(JourneyRepository $journeyRepository): Response
    {

        /**
         * @var $journey Journey
         */
        $journey = $journeyRepository->findOneBy(["title" => "Le Mystère du Professeur Verdant"]);

        $hotspots = array_map(function (Area $a) {
            return new HotspotDTO(
                lat: $a->getLatGPS(),
                lng: $a->getLngGPS(),
                title: $a->getTitle(),
                slug: $a->getSlug()
            );
        }, $journey->getAreas()->toArray());

        return $this->render('game/select_area.html.twig', [
            'hotspots' => $hotspots
        ]);
    }

    #[Route('/rejoindre-zone/{slug}/{id?}', name: 'reach_area', requirements: ['slug' => Requirement::ASCII_SLUG, 'id' => Requirement::DIGITS])]
    public function reach_area(string $slug, AreaRepository $areaRepository, ?string $id = ""): Response
    {
        /**
         * @var $area Area
         */
        $area = $areaRepository->findOneBy(["slug" => $slug]);

        if (!$area) {
            $this->addFlash("danger", "Zone Introuvable");
            return $this->redirectToRoute('home');
        }

        $hotspotDest = new HotspotDTO(
            lat: $area->getLatGPS(),
            lng: $area->getLngGPS()
        );

        if (!$id || $id === "") {
            # Créer une nouvelle partie
            $id = "0";
        }

        return $this->render('game/reach_area.html.twig', [
            'hotspotDest' => $hotspotDest,
            'gameID' => $id
        ]);
    }

    #[Route('/jeu/{gameID}/', name: 'play', requirements: ['gameID' => Requirement::DIGITS])]
    public function play(int $gameID, GameRepository $gameRepository)
    {


        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }


        $ongoingChallenge = new OngoingChallengeDTO(
            numberOfAreasCompleted: $game->getNumberOfAreasCompleted(),
            numberOfAreas: $game->getNumberOfAreas(),
            type: $game->getOngoingChallenge()->getChallenge()->getType(),
            description: $game->getOngoingChallenge()->getChallenge()->getDescription(),
            image: $game->getOngoingChallenge()->getChallenge()->getImage(),
            lastHint: $game->getOngoingChallenge()->getLastHintTxt(),
            lastScannedSpecies: $game->getOngoingChallenge()->getScannedSpecies()->last()->getLatinName()
        );

        return $this->render( 'game/play.html.twig', [
            'ongoingChallenge' => $ongoingChallenge,
            'gameID' => $gameID
        ]);
    }

    #[Route('/jeu/{gameID}/carte', name: 'play.map', requirements: ['gameID' => Requirement::DIGITS])]
    public function map(int $gameID, GameRepository $gameRepository)
    {

        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        $species = $game->getOngoingChallenge()->getChallenge()->getSpecies()->toArray();

        $areaLat = $game->getOngoingChallenge()->getChallenge()->getArea()->getLatGPS();
        $areaLng = $game->getOngoingChallenge()->getChallenge()->getArea()->getLngGPS();

        $hotspots = array_map(function (Species $s): HotspotDTO {
            return new HotspotDTO(
                lat:$s->getLatGPS(),
                lng:$s->getLngGPS(),
                title: "Espèce n°".$s->getId()
            );
        }, $species);

        return $this->render('game/map.html.twig', [
            'hotspots' => $hotspots,
            'areaLat' => $areaLat,
            'areaLng' => $areaLng,
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
    public function species_information(int $gameID, int $speciesID, SpeciesRepository $speciesRepository)
    {
        $species = $speciesRepository->findOneBy(['id' => $speciesID]);

        if (!$species) {
            $this->addFlash("danger", "L'espèce n°".$speciesID." n'existe pas");
            return $this->redirectToRoute('play.scanner', [
                'gameID' => $gameID
            ]);
        }

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
    public function hints(int $gameID, GameRepository $gameRepository) : Response
    {
        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        $allHints = $game->getOngoingChallenge()->getChallenge()->getHints();

        $lastHint = $game->getOngoingChallenge()->getLastHint();

        $hints = array_slice($allHints, 0, $lastHint);

        return $this->render('game/hints.html.twig', [
            'gameID' => $gameID,
            'hints' => $hints
        ]);
    }

    #[Route('/jeu/{gameID}/especes-scannees', name: 'play.scanned_species', requirements: ['gameID' => Requirement::DIGITS])]
    public function scanned_species(int $gameID, GameRepository $gameRepository) : Response
    {

        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        $scannedSpecies = $game->getOngoingChallenge()->getScannedSpecies()->toArray();

        $allScannedSpeciesDTO = array_map(function (Species $s): ScannedSpeciesDTO {
            return new ScannedSpeciesDTO(
                id: $s->getId(),
                latinName: $s->getLatinName()
            );
        }, $scannedSpecies);


        return $this->render('game/scanned_species.html.twig', [
            'gameID' => $gameID,
            'allScannedSpecies' => $allScannedSpeciesDTO
        ]);
    }

    #[Route('/jeu/{gameID}/quiz', name: 'play.quiz', requirements: ['gameID' => Requirement::DIGITS])]
    public function quiz(int $gameID, GameRepository $gameRepository)
    {

        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        $quizzes = $game->getOngoingChallenge()->getChallenge()->getSpeciesToGuess()->getQuizzes()->toArray();
        $quiz = $quizzes[array_rand($quizzes)];

        $journeyEnding = ($game->getNumberOfAreasCompleted() === ($game->getNumberOfAreas() - 1));

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
