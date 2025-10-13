<?php

namespace App\Controller;

use App\Entity\Flash;
use App\Form\FlashType;
use League\Glide\ServerFactory;
use App\Repository\UserRepository;
use App\Repository\FlashRepository;
use App\Service\ConvertImageFormat;
use App\Repository\CategoryRepository;
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
    public function index(
        Request $request,
        EntityManagerInterface $em,
        FlashRepository $flashRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository): Response
    {   

        if($this->getUser())
            $likedFlashs = $flashRepository->likedFLashs($this->getUser()->getId());
        else
            $likedFlashs = null;


        $form = $this->createForm(FlashType::class);
        $form->handleRequest($request);

        $page = $request->query->getInt("page", 1);
        $flashs = $flashRepository->paginateFlashs($page);
        $categories = $categoryRepository->findAll([], []);
        $maxPages = ceil($flashs->getTotalItemCount() / 8);
        
        if($request->get("ajax"))
        {
            $idCategories = $request->get("categories");

            if($idCategories)
            {
                $filtredFlashs = $flashRepository->paginateFlashsWithCategories($page, $idCategories);
                $maxPages = ceil($filtredFlashs->getTotalItemCount() / 8);

                return $this->render("flash/_flashContainer.html.twig", [
                "flashs" => $filtredFlashs,
                "maxPages" => $maxPages,
                "categories" => $categories,
                ]);
            }
            else
            {
                return $this->render('flash/_flashContainer.html.twig', [
                    "flashs" => $flashs,
                    "maxPages" => $maxPages,
                    "categories" => $categories,
                ]);
            }
        }


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
                
                if($convertImage->convertImageToWebp($_FILES['flash'], $flash, null) != false)
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
            'flash_form' => $form,
            "likedFlashs" => $likedFlashs,
            'flashs' => $flashs,
            'maxPages' => $maxPages,
            'categories' => $categories,
            "logoNom" => 1,
            "footer" => 1,
        ]);
    }

    #[Route('/deleteFlash/{id}', name: 'delete_flash')]
    public function deleteFlash(int $id, EntityManagerInterface $em, FlashRepository $flashRepository)
    {
        $flash = $flashRepository->findOneBy(["id" => $id], []);

        $em->remove($flash);
        $em->flush();

        $this->addFlash("success", "Le flash a bien été supprimé.");
        return $this->redirectToRoute("app_flash");
        
    }

    
}
