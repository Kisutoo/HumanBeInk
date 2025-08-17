<?php

namespace App\Controller;

use App\Form\FlashType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FlashController extends AbstractController
{
    #[Route('/flash', name: 'app_flash')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FlashType::class);
        $form->handleRequest($request);

        return $this->render('flash/index.html.twig', [
            'controller_name' => 'FlashController',
            'flash_form' => $form
        ]);
    }
}
