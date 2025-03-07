<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Journey;
use App\Entity\OngoingChallenge;
use App\Entity\User;
use App\Form\JourneyType;
use App\Repository\AreaRepository;
use App\Repository\GameRepository;
use App\Repository\JourneyRepository;
use App\Repository\SpeciesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use function MongoDB\BSON\toJSON;

#[Route('/api', name: 'api.')]
final class ApiController extends AbstractController
{

    #[Route('/validate-answer', name: 'validate_answer', methods: ["POST"])]
    public function validateAnswer(
        Request $request,
        GameRepository $gameRepository,
        SpeciesRepository $speciesRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        // Récupération des données depuis le corps de la requête
        $data = json_decode($request->getContent(), true);
        $gameID = $data['gameID'] ?? null;
        $speciesID = $data['speciesID'] ?? null;

        // On vérifie d'abord la validité de la partie et de l'espèce
        $game = $gameRepository->find($gameID);
        $species = $speciesRepository->find($speciesID);

        // Si les donneés n'existe pas, on renvoie un message d'erreur
        if (!$game || !$species) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Validate-Answer : Partie ou espèce introuvable.'
            ], 400);
        }

        // Logique de validation : vérifier si l'espèce correspond à la réponse
        $isCorrect = $game->getOngoingChallenge()->getChallenge()->getSpeciesToGuess()->getId() === $species->getId();

        $journeyEnd = ($game->getNumberOfAreasCompleted() + 1 >= $game->getNumberOfAreas());

        if ($isCorrect && $journeyEnd) {
            $game->setIsFinished(true);
            $entityManager->persist($game);
            $entityManager->flush();
        }

        // Retourner la réponse en fonction de la vérification de la réponse
        return new JsonResponse([
            'success' => true,
            'message' => [
                'resultSpeciesGuess' => $isCorrect,
                'journeyEnd' => $journeyEnd
            ]
        ]);
    }

    #[Route("/new-game", name: "new_game", methods: ["POST"])]
    public function new_game(
        Request $request,
        JourneyRepository $journeyRepository,
        AreaRepository $areaRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {

        // Récupération des données depuis le corps de la requête
        $data = json_decode($request->getContent(), true);
        $journeyID = $data['journeyID'] ?? null;
        $areaID = $data['areaID'] ?? null;
        $player = $data['user'] ?? null;

        // Récupération des données
        /**
         * @var $journey Journey
         */
        $journey = $journeyRepository->find($journeyID);
        $area = $areaRepository->find($areaID);
        $user = $userRepository->find($player);


        // Si les donneés n'existe pas, on renvoie un message d'erreur
        if (!$journey || !$area || !$user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Next-Game : Parcours ou Zone ou Joueur introuvable.'
            ], 400);
        }

        // Obtention d'un challenge aléatoire dans la zone
        $challenges = $area->getChallenges()->toArray();
        $challenge = $challenges[array_rand($challenges)];

        // Création du challenge qui va être joué
        $ongoingChallenge = (new OngoingChallenge())
            ->setChallenge($challenge)
            ->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($ongoingChallenge);

        $numStartingArea = array_search($area, $journey->getAreas()->toArray());

        // Création de la nouvelle partie
        $newGame = (new Game())
            ->setUser($user)
            ->setJourney($journey)
            ->setNumStartingArea($numStartingArea)
            ->setOngoingChallenge($ongoingChallenge)
            ->setNumberOfAreas(count($journey->getAreas()))
            ->setNumberOfAreasCompleted(0)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->persist($newGame);

        $entityManager->flush();

        $newGameID = $newGame->getId();

        // Retourner l'id de la nouvelle partie
        return new JsonResponse([
            'success' => true,
            'message' => [
                'gameID' => $newGameID
            ]
        ]);
    }

    #[Route("/new-hint", name: "new_hint", methods: ["POST"])]
    public function new_hint(
        Request $request,
        GameRepository $gameRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {

        // Récupération des données depuis le corps de la requête
        $data = json_decode($request->getContent(), true);
        $gameID = $data['gameID'] ?? null;

        // Récupération des données
        $game = $gameRepository->find($gameID);


        // Si les donneés n'existe pas, on renvoie un message d'erreur
        if (!$game) {
            return new JsonResponse([
                'success' => false,
                'message' => 'New-Hint : Partie introuvable.'
            ], 400);
        }


        // Obtention du challenge en cours
        $ongoingChallenge = $game->getOngoingChallenge();

        // Obtention d'un nouvel indice
        $hintAvailable = $ongoingChallenge->newHint();

        // Mise à jour dans la base de donnée
        $entityManager->persist($ongoingChallenge);
        $entityManager->flush();

        // Retourner l'id de la nouvelle partie
        return new JsonResponse([
            'success' => true,
            'message' => [
                'hintAvailable' => $hintAvailable
            ]
        ]);
    }

    #[Route("/next-area", name: "next_area", methods: ["POST"])]
    public function next_area(
        Request $request,
        GameRepository $gameRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {

        // Récupération des données depuis le corps de la requête
        $data = json_decode($request->getContent(), true);
        $gameID = $data['gameID'] ?? null;

        // Récupération des données
        $game = $gameRepository->find($gameID);

        // Si les donneés n'existe pas, on renvoie un message d'erreur
        if (!$game ) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Next-Area : Partie introuvable.'
            ], 400);
        }

        $temp = $game->getNumberOfAreasCompleted();

        # Obtention de la nouvelle zone
        $newArea = $game->getNextArea();
        $newAreaID = $newArea->getId();

        dump($newArea);

        # Obtention du nouveau challenge
        $challenges = $newArea->getChallenges()->toArray();
        $challenge = $challenges[array_rand($challenges)];

        # Reset le ongoingChallenge avec le nouveau challenge
        $ongoingChallenge = $game->getOngoingChallenge();
        $ongoingChallenge->setChallenge($challenge)
            ->setLastHint(0)
            ->clearScannedSpecies()
            ->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($ongoingChallenge);
        $entityManager->persist($game);

        $entityManager->flush();

        // Retourner l'id de la nouvelle partie
        return new JsonResponse([
            'success' => true,
            'message' => [
                'newAreaID' => $newAreaID
            ]
        ]);
    }

}
