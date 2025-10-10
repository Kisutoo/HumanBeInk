<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NicknameType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public const SCOPES = [
        "google" => [],
    ];


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, RateLimiterFactoryInterface $anonymousApiLimiter, Request $request): Response
    {
        if($this->getUser())
        // Si un utilisateur est connecté
        {
            return $this->redirectToRoute("app_accueil");
            // Redirection vers la page d'accueil
        }

        // Stocke l'erreur de connexion si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();
        $blocked = Null;

        // Create prend un identifiant unique (ici l'ip du client) pour suivre le nombre de requêtes ou tentatives pour chaque utilisateur séparément
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        if($error)
        // Si il y a une erreur lors de la tentative de connexion (identifiant et mot de passe non correspondants)
        {
            if (false === $limiter->consume(1)->isAccepted()) {
                // consume(1) : on tente de consommer 1 unité du quota du limiteur.
                // isAccepted() : renvoie true si la consommation est autorisée (quota non dépassé), sinon false. 
                // Donc, si le retour est false, cela signifie que l’IP a dépassé le nombre autorisé de tentatives échouées.

                
                // On ajoute un message flash pour informer l’utilisateur qu’il doit attendre.
                $this->addFlash("error", "Il y a eu trop de tentatives infructueuses, veuillez réessayer dans 10 minutes.");
    
                // On redirige l’utilisateur vers la page d’accueil.
                return $this->redirectToRoute("app_accueil");
            }
        }


        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            "blocked" => $blocked,
            "logoNom" => 0,
            "footer" => 0,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route("/oauth/connect/{service}", name: 'auth_oauth_connect', methods: ['GET'])]
    public function connect(string $service, ClientRegistry $clientRegistry): RedirectResponse
    {
        if (! in_array($service, array_keys(self::SCOPES), true)) {
            throw $this->createNotFoundException();
        }

        return $clientRegistry
            ->getClient($service)
            ->redirect(self::SCOPES[$service]);
    }

    #[Route('/oauth/check/{service}', name: 'auth_oauth_check', methods: ['GET', 'POST'])]
    public function check(): Response
    {
        return new Response(status: 200);
    }
    

}
