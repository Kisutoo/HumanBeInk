<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;


final class ContactController extends AbstractController
{
    #[Route('/contact/{image}', name: 'app_contact_with_flash')]
    #[Route('/contact', name: 'app_contact')]
    public function index(
    ?string $image,
    Request $request, 
    EntityManagerInterface $em, 
    MailerInterface $mailer,
    #[Autowire(service: 'mailer.transport_factory')] $factory,
    #[Autowire('%env(MAILER_DSN)%')] string $dsn,
    Recaptcha3Validator $recaptcha3Validator
    ): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        // Si le formulaire est soumis et valide
        {
            $score = $recaptcha3Validator->getLastResponse()->getScore();
            // RecaptchaV3 analyse le comportement de la souris, du clavier etc puis attribut un score à ce comportement.
            // C'est ce qu'on vient récupérer ici.

            if($score >= 0.5)
                // L'utilisateur est probablement humain
            {
                $transport = $factory->fromString($dsn);
                
                $envoyeur = $form->get("email")->getData();
                $nomPrenom = $form->get("name")->getData();
                $project = $form->get("project")->getData(); // On pourrait faire ça en une seule ligne en mettant tout dans un tableau associatif mais je trouve que faire de cette manière est plus lisible / représentatif
                $taille = $form->get("size")->getData();
                $zone = $form->get("area")->getData();
                $imageForm = $form->get("image")->getData();
                $imageUploadedPath = $imageForm->getPathname();
                $imageMimeType = $imageForm->getMimetype();
                $flashName = $imageForm->getClientOriginalName();


                $email = (new TemplatedEmail())
                ->from($envoyeur)
                ->to("poivron@humanbeink.com") // Email envoyé au tatoueur pour le projet du client
                ->subject('Demande de tatouage/renseignements')
                ->text('Sending emails is fun again!')
                ->htmlTemplate("emails/contact.html.twig");
                if($imageMimeType == "text/plain")
                {
                    $email->addPart((new DataPart(new File("../public/img/flashs/" . $flashName ), "inspiration", "image/webp"))->asInline());
                }
                else
                {
                    $email->addPart((new DataPart(new File($imageUploadedPath), "inspiration", $imageMimeType))->asInline());
                }
                $email->context([
                    "envoyeur" => $envoyeur,
                    "nomPrenom" => $nomPrenom,
                    "project" => $project,
                    "taille" => $taille,
                    "zone" => $zone,
                    "image" => $imageForm
                ]);

    
                $transport->send($email);
    
                $this->addFlash("success", "Email envoyé.");
                return $this->redirectToRoute("app_contact");
            }
            else
                // L'utilisateur est surement un robot
            {
                // Ajout et affichage d'un message d'erreur
                $this->addFlash("error", "Activité suspecte détectée.");
                // Redirection vers la page de contact
                return $this->redirectToRoute("app_contact");
            }
            
        }

        if(!$image)
        {
            $image = null;
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactType' => $form,
            'previewImage' => $image,
            "logoNom" => 1,
            "footer" => 1,
        ]);
    }
}
