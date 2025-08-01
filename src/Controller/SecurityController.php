<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, RateLimiterFactoryInterface $anonymousApiLimiter, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $blocked = Null;

        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            
            $this->addFlash("error", "Il y a eu trop de tentatives infructueuses, veuillez réessayer dans 10 minutes.");

            return $this->redirectToRoute("app_accueil");
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

    #[Route(path: '/profile', name: 'app_profile')]
    public function profile(Request $request): Response
    {
        $user = $this->getUser();
        if($user)
        {   
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            return $this->render("security/profile.html.twig", [
                'registrationForm' => $form,
            ]);
        }
        else
        {
            return back();
        }

        
    }
}
