<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FlashController extends AbstractController
{
    #[Route('/flash', name: 'app_flash')]
    public function index(): Response
    {
        return $this->render('flash/index.html.twig', [
            'controller_name' => 'FlashController',
        ]);
    }
}
