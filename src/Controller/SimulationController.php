<?php

namespace App\Controller;

use App\Form\SimulationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SimulationController extends AbstractController
{
    #[Route('/simulation', name: 'app_simulation')]
    public function index(Request $request): Response
    {   
        $form = $this->createForm(SimulationType::class);
        $form->handleRequest($request);
        

        return $this->render('simulation/index.html.twig', [
            'controller_name' => 'SimulationController',
            'form' => $form
        ]);
    }
}
