<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(User $user, EntityManagerInterface $em): Response
    {
        
        return $this->render('accueil/index.html.twig', [
            "logoNom" => 0,
            "footer" => 1,
        ]);
    }
}
