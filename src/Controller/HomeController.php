<?php

namespace App\Controller;

use App\DTO\GalleryDTO;
use App\Entity\Badge;
use App\Entity\Glossary;
use App\Entity\Settings;
use App\Entity\Species;
use App\Entity\Statistics;
use App\Entity\User;
use App\Repository\BadgeRepository;
use App\Repository\GlossaryRepository;
use App\Repository\SettingsRepository;
use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();

        $profilePicture = "images/default_profile_picture.png";
        if ($user) {
            $profilePicture = $user->getProfilePicture();
        }

        return $this->render('home/index.html.twig', [
            'profilePicture' => $profilePicture
        ]);
    }

    #[Route('/explication', name: 'explanation')]
    #[Route('/jeu/{gameID}/explication', name: 'play.explanation', requirements: ['gameID' => '\d+'])]
    public function explanation(?int $gameID = null): Response
    {
        return $this->render('home/explanation.html.twig', [
            'gameID' => $gameID
        ]);
    }

    #[Route('/compte', name: 'account')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function account(BadgeRepository $badgeRepository, SpeciesRepository $speciesRepository, GlossaryRepository $glossaryRepository): Response
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();
        $username = $user->getUsername();
        $profilePicture = $user->getProfilePicture();

        $badges = $badgeRepository->findAll();

        $userStatistics = $user->getStatistics();

        if (!($userStatistics instanceof Statistics)) {
            $this->addFlash("danger", "Statistiques Introuvable");
            return $this->redirectToRoute('home');
        }

        $badgesDTO = array_map(function (Badge $b) use ($userStatistics): GalleryDTO {
            return new GalleryDTO(
                image: $b->getImage(),
                title: $b->getTitle(),
                legend: $b->getLegend(),
                unlocked: $b->isUnlocked($userStatistics)
            );
        }, $badges);

        $species = $speciesRepository->findAll();

        $glossary = $glossaryRepository->findOneByUser($user);

        if (!($glossary instanceof Glossary)) {
            $this->addFlash("danger", "Glossaire Introuvable");
            return $this->redirectToRoute('home');
        }

        /**
         * @var $unlockedSpecies Collection<int, Species>
         */
        $unlockedSpecies = $glossary->getUnlockedSpecies();

        $glossaryDTO = array_map(function (Species $s) use ($unlockedSpecies) {
            return new GalleryDTO(
                image: $s->getImages()[0],
                title: $s->getLatinName(),
                legend: $s->getCommonName(),
                unlocked: $unlockedSpecies->contains($s)
            );
        },$species);

        return $this->render('home/account.html.twig', [
            'badges' => $badgesDTO,
            'glossary' => $glossaryDTO,
            'profilePicture' => $profilePicture,
            'username' => $username
        ]);
    }

    #[Route("/parametres/", name: "settings")]
    #[Route("jeu/{gameID?}/parametres/", name: "play.settings", requirements: ['gameID' => Requirement::DIGITS])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function settings(Request $request, SettingsRepository $settingsRepository, ?int $gameID = null): Response
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();

        // Verifie si l'utilisateur a le role ROLE_ADMIN
        $isAdminn = in_array('ROLE_ADMIN', $user->getRoles());

        $settings = $user->getSettings();
        if (!($settings instanceof Settings)) {
            $this->addFlash("danger", "Paramètres Introuvable");
            return $this->redirectToRoute('home');
        }

        $tgPath = $request->query->get('tgPath');

        if ($tgPath) {
            $tgPath = urldecode($tgPath); // Décoder une seule fois si présent

            // Vérifier si $tgPath contient un autre tgPath en paramètre
            $query = parse_url($tgPath, PHP_URL_QUERY);
            if ($query) {
                parse_str($query, $queryParams);
                if (!empty($queryParams['tgPath'])) {
                    $tgPath = urldecode($queryParams['tgPath']); // Utiliser directement le bon tgPath
                }
            }
        }

        return $this->render('home/settings.html.twig', [
            'gameID' => $gameID,
            'isAdmin' => $isAdminn,
            'referer' => $tgPath
        ]);

    }

}
