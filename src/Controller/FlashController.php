<?php

namespace App\Controller;

use App\Entity\Flash;
use App\Form\FlashType;
use App\Repository\FlashRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
                $image = $_FILES['flash']['name'];
                $tmpName = $_FILES['flash']['tmp_name'];
                $size = $_FILES['flash']['size'];
                $error = $_FILES['flash']['error'];
                // Assignation de différentes variables en fonction des données contenues dans la superglobale _FILES
                $extensions = ['webp'];
                $tabExtension = explode('.', $image['image']);
                // On sépare le nom du flash en plusieurs partie en fonction dès que l'on tombe sur le caractère rentré en paramètres, donc le "."
                $extension = strtolower(end($tabExtension));
                // On met en minuscule le nom de l'extension pour pouvoir la comparer à l'extension du dessus, plus tard
                if(in_array($extension, $extensions))
                // Si le fichier séléctionné est un fichier webp
                {
                    $uniqueName = uniqid('', true);
                    //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                    $IdFlash = $uniqueName . "." . $extension;
                    //$affiche = 5f586bf96dcd38.73540086.jpg 
                    $flash = $flash->setImage($IdFlash);

                    $em->persist($flash);
                    $em->flush();

                    move_uploaded_file($tmpName['image'], '../public/img/flashs/'.$IdFlash);
                    //Déplace le fichier contenu dans le tableau file, au dossier public/img/affiche

                    $this->addFlash('success', 'Le nouveau flash a bien été ajouté.');
                    return $this->redirectToRoute('app_flash');
                }
                else
                // Si le fichier séléctionné n'est pas un ficher webp
                {
                    $this->addFlash("error", "Veuillez séléctionner un fichier webp.");
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
