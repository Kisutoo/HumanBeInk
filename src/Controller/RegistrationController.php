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

        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $score = $recaptcha3Validator->getLastResponse()->getScore();

            if(false === $limiter->consume(1)->isAccepted())
            {
                $this->addFlash("error", "Un nouvel utilisateur vient déjà d'être créé, veuillez réessayer dans 2 minutes.");
                $this->redirectToRoute("app_register");
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form,
                ]);
                
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
