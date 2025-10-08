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
        // $user = $this->getUser();
        // $roles = array(
        //     0 => "ROLE_ADMIN"
        // );
        // $user->setRoles($roles);
        // $em->persist($user);
        // $em->flush();


        return $this->render('accueil/index.html.twig', [
            "logoNom" => 0,
            "footer" => 1,
        ]);
    }
}
