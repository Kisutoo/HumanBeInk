<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ContactController extends AbstractController
{
    #[Route('/contact/{image}', name: 'app_contact_with_flash')]
    #[Route('/contact', name: 'app_contact')]
    public function index(?string $image, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

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
