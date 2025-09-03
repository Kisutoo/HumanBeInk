<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Transport\TransportInterface;


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
    #[Autowire('%env(MAILER_DSN)%')] string $dsn
    ): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $transport = $factory->fromString($dsn);
            $envoyeur = $form->get("email")->getData();

            $email = (new Email())
            ->from($envoyeur)
            ->to("poivron@humanbeink.com") // Email envoyé au tatoueur pour le projet du client
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');


            $transport->send($email);

            $this->addFlash("success", "Email envoyé.");
            return $this->redirectToRoute("app_contact");
        }

        if(!$image)
        {
            $image = null;
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactType' => $form,
            'previewImage' => $image,
        ]);
    }
}
