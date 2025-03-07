<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Badge;
use App\Entity\Challenge;
use App\Entity\Journey;
use App\Entity\Quiz;
use App\Entity\Species;
use App\Form\AreaType;
use App\Form\BadgeType;
use App\Form\ChallengeType;
use App\Form\JourneyType;
use App\Form\QuizType;
use App\Form\SpeciesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Requirement\Requirement;
use Twig\Environment;

#[Route("/admin", name: 'admin.')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Environment $twig;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    /**
     * Afficher la liste des entités
     */
    private function listAction(string $entityType, string $template, array $parameters = []): Response
    {
        $entityClass = self::$manageableEntities[$entityType][0];
        $fieldsToDisplay = self::$manageableEntities[$entityType][2];

        $this->twig->addFunction(new \Twig\TwigFunction('isObject', function ($value) {
            return is_object($value);
        }));

        $repository = $this->entityManager->getRepository($entityClass);  // Utiliser le repository via EntityManager
        $entities = $repository->findAll();

        return $this->render($template, array_merge($parameters, [
            'entities' => $entities,
            'entityType' => $entityType,
            'fieldsToDisplay' => $fieldsToDisplay
        ]));
    }

    /**
     * Créer ou éditer une entité
     */
    private function formAction(string $entityType, Request $request, string $template, ?object $entity = null): RedirectResponse|Response
    {
        $entityClass = self::$manageableEntities[$entityType][0];
        $entityForm = self::$manageableEntities[$entityType][1];

        $className = ((new \ReflectionClass($entityClass))->getShortName());
        $title = "Modifier ".ucfirst($entityType);

        if ($entity === null) {
            if (class_exists($entityClass)) {
                // Crée une nouvelle instance de la classe
                $entity = new $entityClass();
            }
            $title = "Créer un(e) ".ucfirst($entityType);
        }

        $form = $this->createForm($entityForm, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->addFlash('success', 'L\'entité a été enregistrée avec succès.');

            return $this->redirectToRoute('admin.' . strtolower((new \ReflectionClass($entity))->getShortName()) . '.index');
        }

        return $this->render($template, [
            'form' => $form->createView(),
            'entity' => $entity,
            'title' => $title,
            'entityType' => $entityType
        ]);
    }

    /**
     * Supprimer une entité
     */
    private function deleteAction(string $entityType, $entity): RedirectResponse
    {
        $entityClass = self::$manageableEntities[$entityType][0];

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        $this->addFlash('success', 'L\'entité a été supprimée.');

        return $this->redirectToRoute('admin.' . strtolower((new \ReflectionClass($entityClass))->getShortName()) . '.index');
    }

    private static array $manageableEntities = [
        'especes' => [Species::class, SpeciesType::class, ["latinName", "commonName", "slug", "area"]],
        'quiz' => [Quiz::class, QuizType::class, ["question"]],
        'challenges' => [Challenge::class, ChallengeType::class, ["type", "area", "speciesToGuess"]],
        'zones' => [Area::class, AreaType::class, ["title", "slug", "infos"]],
        'parcours' => [Journey::class, JourneyType::class, ["title", "slug", "infos", "areas"]],
        'badges' => [Badge::class, BadgeType::class, ["title", "legend", "unlockingCondition"]]
    ];


    // Routes pour l'entité 'Species'
    #[Route('/{entityType}', name: 'entity.index')]
    public function entityIndex(string $entityType): Response
    {
        return $this->listAction($entityType, 'admin/list.html.twig');
    }

    #[Route('/{entityType}/creer', name: 'entity.create')]
    public function entityCreate(Request $request, string $entityType): Response
    {
        return $this->formAction($entityType, $request, 'admin/edit.html.twig');
    }

    #[Route('/{entityType}/{id}', name: 'entity.edit')]
    public function entityEdit(Request $request, string $entityType, int $id): Response
    {
        $entityClass = self::$manageableEntities[$entityType][0];
        $entity = $this->entityManager->getRepository($entityClass)->find($id);

        return $this->formAction($entityType, $request, 'admin/edit.html.twig', $entity);
    }

    #[Route('/{entityType}/{id}/supprimer', name: 'entity.delete', requirements: ['id' => Requirement::DIGITS])]
    public function entityDelete(string $entityType, int $id): Response
    {
        $entityClass = self::$manageableEntities[$entityType][0];
        $entity = $this->entityManager->getRepository($entityClass)->find($id);

        return $this->deleteAction($entityType, $entity);
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [

        ]);
    }
}

