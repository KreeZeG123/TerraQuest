<?php

namespace App\Controller;

use App\DTO\HotspotDTO;
use App\DTO\OngoingChallengeDTO;
use App\DTO\OngoingGameDTO;
use App\DTO\ScannedSpeciesDTO;
use App\Entity\Area;
use App\Entity\Game;
use App\Entity\Journey;
use App\Entity\Species;
use App\Entity\User;
use App\Repository\AreaRepository;
use App\Repository\GameRepository;
use App\Repository\JourneyRepository;
use App\Repository\SpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class GameController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/parties', name: 'games')]
    public function games(GameRepository $gameRepository): Response
    {

        $isConnected = false;
        $games = [];

        /**
         * @var $user User
         */
        $user = $this->getUser();
        if ($user !== null) {
            $games = $gameRepository->findByUser($user);
            $isConnected = true;
        }

        $ongoingGames = array_map(function (Game $g): OngoingGameDTO {
            $gameFinished = $g->isFinished();

            if ($gameFinished) {
                $title = "Terminé : ";
            }else {
                $title = "Zone " . $g->getNumberOfAreasCompleted()+1 . "/" . $g->getNumberOfAreas() .
                    " \"";
            }

            $title = $title .
                $g->getOngoingChallenge()->getChallenge()->getArea()->getTitle() .
                "\" - " .
                ($g->getUpdatedAt() ? $g->getUpdatedAt()->format("d/m/Y") : "Date inconnue")
            ;

            return new OngoingGameDTO(
                title: $title,
                gameID: $g->getId(),
                isFinished: $g->isFinished()
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
        $journeyID = $journey->getId();

        $hotspots = array_map(function (Area $a) {
            return new HotspotDTO(
                lat: $a->getLatGPS(),
                lng: $a->getLngGPS(),
                title: $a->getTitle(),
                areaID: $a->getId()
            );
        }, $journey->getAreas()->toArray());

        return $this->render('game/select_area.html.twig', [
            "journeyID" => $journeyID,
            'hotspots' => $hotspots
        ]);
    }

    #[Route('parcours/{journeyID}/rejoindre-zone/{areaID}', name: 'reach_area', requirements: ['journeyID' => Requirement::DIGITS, 'areaID' => Requirement::DIGITS,])]
    #[Route('/jeu/{gameID}/rejoindre-zone/{areaID}', name: 'play.reach_area', requirements: ['areaID' => "\d+|__areaID__", 'id' => Requirement::DIGITS])]
    public function reach_area(
        string $areaID,
        AreaRepository $areaRepository,
        GameRepository $gameRepository,
        ?string $journeyID = null,
        ?string $gameID = "__gameID__"
    ): Response
    {
        if ($gameID !== "__gameID__") {
            $game = $gameRepository->findOneBy(['id' => $gameID]);

            if (!$game) {
                $this->addFlash("danger", "Partie Introuvable");
                return $this->redirectToRoute('home');
            }

            /**
             * @var $user User
             */
            $user = $this->getUser();

            // Vérification du joueur
            $checkPlay = $this->checkValidPlayer($user, $game);
            if ($checkPlay) {
                return $checkPlay;
            }

            if ($game->isFinished()) {
                $this->addFlash("danger", "Cette partie est terminée");
                return $this->redirectToRoute('home');
            }

            $journeyID = $game->getJourney()->getId();
        }

        /**
         * @var $area Area
         */
        $area = $areaRepository->find($areaID);

        if (!$area) {
            $this->addFlash("danger", "Zone Introuvable");
            return $this->redirectToRoute('home');
        }

        $hotspotDest = new HotspotDTO(
            lat: $area->getLatGPS(),
            lng: $area->getLngGPS()
        );

        /**
         * @var $user User
         */
        $user = $this->getUser();
        $userID = $user ? $user->getId() : -1;

        return $this->render('game/reach_area.html.twig', [
            'hotspotDest' => $hotspotDest,
            'gameID' => $gameID,
            'journeyID' => $journeyID,
            'areaID' => $areaID,
            'userID' => $userID
        ]);
    }

    #[Route('/jeu/{gameID}/', name: 'play', requirements: ['gameID' => '\d+|__gameID__'])]
    public function play(int $gameID, GameRepository $gameRepository): Response
    {

        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
            return $this->redirectToRoute('home');
        }

        $scannedSpecies = $game->getOngoingChallenge()->getScannedSpecies();
        $lastScannedSpecies = $scannedSpecies->isEmpty() ? "Aucune espèces scannées" : $scannedSpecies->last()->getLatinName();

        $ongoingChallenge = new OngoingChallengeDTO(
            numberOfAreasCompleted: $game->getNumberOfAreasCompleted(),
            numberOfAreas: $game->getNumberOfAreas(),
            type: $game->getOngoingChallenge()->getChallenge()->getType(),
            description: $game->getOngoingChallenge()->getChallenge()->getDescription(),
            image: $game->getOngoingChallenge()->getChallenge()->getImage(),
            lastHint: $game->getOngoingChallenge()->getLastHintTxt(),
            lastScannedSpecies: $lastScannedSpecies
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

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
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
    public function scanner(int $gameID, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
            return $this->redirectToRoute('home');
        }

        return $this->render('game/scanner.html.twig', [
            'gameID' => $gameID
        ]);
    }

    #[Route('jeu/{gameID}/informations-espece/{speciesID}', name: 'play.species_information', requirements: ['gameID' => Requirement::DIGITS, 'speciesID' => '\d+|__SPECIESID__'])]
    public function species_information(
        int $gameID,
        int $speciesID,
        SpeciesRepository $speciesRepository,
        GameRepository $gameRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
            return $this->redirectToRoute('home');
        }

        $species = $speciesRepository->findOneBy(['id' => $speciesID]);

        if (!$species) {
            $this->addFlash("danger", "L'espèce n°".$speciesID." n'existe pas");
            return $this->redirectToRoute('play.scanner', [
                'gameID' => $gameID
            ]);
        }

        // Ajoute au espèces scannes
        $ongoingGame = $game->getOngoingChallenge();
        $scannedSpecies = $ongoingGame->getScannedSpecies();
        if (!$scannedSpecies->contains($species)) {
            $scannedSpecies->add($species);
            $this->entityManager->persist($ongoingGame);
            $this->entityManager->flush();
        }

        return $this->render('game/species_information.html.twig', [
            'gameID' => $gameID,
            'species' => $species
        ]);
    }

    #[Route('/jeu/{gameID}/reponse-vraie', name: 'play.correct_guess', requirements: ['gameID' => Requirement::DIGITS])]
    public function correct_guess(int $gameID, GameRepository $gameRepository) : Response
    {
        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
            return $this->redirectToRoute('home');
        }

        return $this->render('game/correct_guess.html.twig', [
            'gameID' => $gameID,
        ]);
    }

    #[Route('/jeu/{gameID}/reponse-fausse', name: 'play.wrong_guess', requirements: ['gameID' => Requirement::DIGITS])]
    public function wrong_guess(int $gameID, GameRepository $gameRepository) : Response
    {
        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
            return $this->redirectToRoute('home');
        }

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

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
            return $this->redirectToRoute('home');
        }

        $ongoingChallenge = $game->getOngoingChallenge();

        $allHints = $ongoingChallenge->getChallenge()->getHints();

        $lastHint = $ongoingChallenge->getLastHint();

        $hints = array_slice($allHints, 0, $lastHint);

        $hintAvailable = $ongoingChallenge->areHintAvaible();

        return $this->render('game/hints.html.twig', [
            'gameID' => $gameID,
            'hints' => $hints,
            'hintAvailable' => $hintAvailable
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

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        if ($game->isFinished()) {
            $this->addFlash("danger", "Cette partie est terminée");
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

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        $quizzes = $game->getOngoingChallenge()->getChallenge()->getSpeciesToGuess()->getQuizzes()->toArray();
        $quiz = $quizzes[array_rand($quizzes)];

        $journeyEnding = (($game->getNumberOfAreasCompleted() + 1) >= $game->getNumberOfAreas());

        return $this->render('game/quiz.html.twig', [
            'gameID' => $gameID,
            'quiz' => $quiz,
            'journeyEnding' => $journeyEnding
        ]);
    }

    #[Route('/jeu/{gameID}/fin-parcours',  name: 'play.journey_ending', requirements: ['gameID' => Requirement::DIGITS])]
    public function journey_ending(int $gameID, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->findOneBy(['id' => $gameID]);

        if (!$game) {
            $this->addFlash("danger", "Partie Introuvable");
            return $this->redirectToRoute('home');
        }

        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Vérification du joueur
        $checkPlay = $this->checkValidPlayer($user, $game);
        if ($checkPlay) {
            return $checkPlay;
        }

        return $this->render('game/journey_ending.html.twig', [
            'gameID' => $gameID,
        ]);
    }

    public function checkValidPlayer(?User $user, Game $game): ?Response
    {
        $valide = false;

        // Valide si aucun joueur n'est enregistré sur la partie
        if ($game->getUser() === null) {
            $valide = true;
        }
        // Valide si c'est le meme joueur qui est enregistré sur la partie
        elseif ($game->getUser() === $user) {
            $valide = true;
        }

        if (!$valide) {
            $this->addFlash("danger", "Ce n'est pas votre partie. Veuillez vous connecter avec le bon compte.");
            return $this->redirectToRoute('home');
        }

        return null;

    }

}
