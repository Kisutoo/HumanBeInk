<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, Recaptcha3Validator $recaptcha3Validator, RateLimiterFactoryInterface $registerLimiter): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $limiter = $registerLimiter->create($request->getClientIp());
        // Création d'un limiter avec l'adresse IP d'un utilisateur pour la création de compte

        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            // On récupère le mot de passe saisi par l'utilisateur dans le formulaire

            // Puis rentre dans l'entité user, ce même mot de passe que l'on va venir
            // hacher afin qu'il ne soit pas stocké en clair dans la base de données
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $score = $recaptcha3Validator->getLastResponse()->getScore();
            // On récupère le score ReCaptcha d'un utilisateur sur une page afin de vérifier s'il s'agit d'un robot

            if($limiter->consume(1)->isAccepted() === false)
            {
                // Ajout d'un message d'erreur pour l'utilisateur
                $this->addFlash("error", "Un nouvel utilisateur vient déjà d'être créé, veuillez réessayer dans 2 minutes.");

                // Redirection vers la page d'inscription
                $this->redirectToRoute("app_register");
            }
            else
            {
                if ($score >= 0.5) 
                {
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash("success", "Nouvel utilisateur créé avec succès.");
                    return $this->redirectToRoute("app_login");
                }
                else
                {
                    $this->addFlash("error", "Activité suspecte détectée.");
                    return $this->redirectToRoute("app_register");
                }
            }

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            "logoNom" => 0,
            "footer" => 0,
        ]);
    }
}
