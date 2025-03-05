<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class SecurityController extends AbstractController
{
    use TargetPathTrait;

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Récupération de l'erreur d'authentification si elle existe
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupération du dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Récupération de la session depuis la requête
        $session = $request->getSession();

        // Récupération de l'URL de redirection stockée dans la session
        $targetPath = $session->get('_security.main.target_path');

        // Si un tgPath est fourni dans l'URL, on le stocke dans la session
        $tgPath = $request->query->get('tgPath');
        if ($tgPath) {
            $tgPath = urldecode($tgPath);
            $session->set('_security.main.target_path', $tgPath);
            $targetPath = $tgPath;
        }

        // Si targetPath est toujours vide, on redirige vers la route "home"
        if (!$targetPath) {
            $targetPath = $this->generateUrl('home');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'referer' => $targetPath
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
