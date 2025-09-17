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
        {
            return $this->redirectToRoute("app_accueil");
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $blocked = Null;

        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        if($error)
        {
            if (false === $limiter->consume(1)->isAccepted()) {
                
                $this->addFlash("error", "Il y a eu trop de tentatives infructueuses, veuillez réessayer dans 10 minutes.");
    
                return $this->redirectToRoute("app_accueil");
            }
        }


        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            "blocked" => $blocked
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
