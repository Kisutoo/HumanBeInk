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
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(NicknameType::class);
        $form->handleRequest($request);
        if($user)
        {   
            if ($form->isSubmitted() && $form->isValid()) {

                if($user->getPseudonyme() == $form->get("pseudonyme")->getData())
                {
                    $this->addFlash("error", "Veuillez saisir un pseudonyme différent.");
                    return $this->redirectToRoute("app_profile");
                }
                else
                {
                    $user->setPseudonyme($form->get("pseudonyme")->getData());
    
                    $entityManager->persist($user);
                    $entityManager->flush($user);
            
                    $this->addFlash("success", "Votre pseudonyme a été modifié avec succès !");
                    return $this->redirectToRoute("app_profile");
                }
            }

            return $this->render("security/profile.html.twig", [
                'nicknameForm' => $form,
            ]);
        }
        else
        {
            $this->addFlash("error", "Vous devez vous connecter.");
            return $this->redirectToRoute("app_accueil");
        } 
    }
    

    #[Route(path: "/delete", name: "app_delete_profile")]
    public function deleteProfile(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if($user)
        {
            $this->container->get('security.token_storage')->setToken(null);

            $entityManager->remove($user);
            $entityManager->flush();
        
            $this->addFlash("success", "Votre compte a été supprimé avec succès.");
            return $this->redirectToRoute("app_accueil");
        }
        else
        {
            $this->addFlash("error", "Personne n'est connecté.");
            return $this->redirectToRoute("app_accueil"); 
        }

    }
}
