<?php

namespace App\Controller;

use App\Service\ConvertImageFormat;
use App\Entity\Flash;
use App\Form\FlashType;
use League\Glide\ServerFactory;
use App\Repository\FlashRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use League\Glide\Responses\SymfonyResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FlashController extends AbstractController
{
    #[Route('/flash', name: 'app_flash')]
    public function index(Request $request, EntityManagerInterface $em, FlashRepository $flashRepository): Response
    {   
        $form = $this->createForm(FlashType::class);
        $form->handleRequest($request);

        $flashs = $flashRepository->findAll([], []);

        if($form->isSubmitted() && $form->isValid())
        {
            $flash = new Flash();

            $flash = $flash->setNom($form->get('name')->getData());
            $flash = $flash->setTaille($form->get('size')->getData());
            $flash = $flash->setCouleur($form->get('color')->getData());
            $flash = $flash->setCategory($form->get('category')->getData());
            
            if(isset($_FILES['flash']))
            // Si le tableau _FILES['affiche'] existe
            {
                $convertImage = new ConvertImageFormat();
                
                if($convertImage->convertImageToWebp($_FILES['flash'], $flash) != false)
                {
                    $em->persist($flash);
                    $em->flush();

                    $this->addFlash('success', 'Le nouveau flash a bien été ajouté.');
                    return $this->redirectToRoute('app_flash');
                }
                else
                // Si le fichier séléctionné n'est pas un ficher webp
                {
                    $this->addFlash("error", "Veuillez séléctionner un fichier valide.");
                    return $this->redirectToRoute("app_flash");
                }
            }
        }


        return $this->render('flash/index.html.twig', [
            'controller_name' => 'FlashController',
            'flash_form' => $form,
            'flashs' => $flashs
        ]);

    }
}
