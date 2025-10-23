<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NicknameType;
use App\Repository\UserRepository;
use App\Repository\FlashRepository;
use App\Repository\TattooRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route(path: '/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager, FlashRepository $flashRepository, CategoryRepository $categoryRepository, TattooRepository $simulationRepository)
    {
        $user = $this->getUser();

        $form = $this->createForm(NicknameType::class);
        $form->handleRequest($request);

        if($user)
        {   
            $page = $request->query->getInt("page", 1);
            $likedFlashs = $flashRepository->paginateLikedFlashs($page, $this->getUser()->getId());
            $maxPages = ceil($likedFlashs->getTotalItemCount() / 8);
            
            $likedFlashsId = $flashRepository->likedFLashs($this->getUser()->getId());
            $simulations = $simulationRepository->getSimulations($this->getUser()->getId());

            $categories = $categoryRepository->findAll([], []);


            if($request->get("ajax"))
            {
                $idCategories = $request->get("categories");

                if($idCategories)
                {
                    $filtredLikedFlashs = $flashRepository->paginateLikedFlashsWithCategories($page, $this->getUser()->getId(), $idCategories);
                    $maxPages = ceil($filtredLikedFlashs->getTotalItemCount() / 8);

                    return $this->render("security/_likedFlashContainer.html.twig", [
                    "likedFlashs" => $filtredLikedFlashs,
                    "maxPages" => $maxPages,
                    "categories" => $categories,
                    ]);
                }
                else
                {
                    return $this->render('security/_likedFlashContainer.html.twig', [
                        "likedFlashs" => $likedFlashs,
                        "maxPages" => $maxPages,
                        "categories" => $categories,
                    ]);
                }
            }
            
            if ($form->isSubmitted() && $form->isValid()) {

                if($user->getPseudonyme() == $form->get("pseudonyme")->getData())
                {
                    $this->addFlash("error", "Veuillez saisir un pseudonyme différent.");
                    return $this->redirectToRoute("app_profile");
                }
                else
                {
                    $user->setPseudonyme($form->get("pseudonyme")->getData());
    
                    $entityManager->persist($user);
                    $entityManager->flush($user);
            
                    $this->addFlash("success", "Votre pseudonyme a été modifié avec succès !");
                    return $this->redirectToRoute("app_profile");
                }
            }

            return $this->render("security/profile.html.twig", [
                'nicknameForm' => $form,
                "likedFlashs" => $likedFlashs,
                "likedFlashsId" => $likedFlashsId,
                "categories" => $categories,
                "simulations" => $simulations,
                "maxPages" => $maxPages,
                "logoNom" => 1,
                "footer" => 1,
            ]);
        }
        else
        {
            $this->addFlash("error", "Vous devez vous connecter.");
            return $this->redirectToRoute("app_login");
        } 
    }


    #[Route(path: "/member/delete", name: "app_delete_profile")]
    public function deleteProfile(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request)
    {
        $user = $this->getUser();
        if($user)
        {
            $request->getSession()->invalidate();
            $this->container->get('security.token_storage')->setToken(null);

            $entityManager->remove($user);
            $entityManager->flush();
        
            $this->addFlash("success", "Votre compte a été supprimé avec succès.");
        }
        else
            $this->addFlash("error", "Personne n'est connecté.");
        
        return $this->redirectToRoute("app_accueil"); 

    }

    #[Route("/member/profile/addFav", name: "add_fav")]
    public function addFlashToFavorites(FlashRepository $flashRepository, Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $id = $request->get("id");
        $id = intval($id);

        $flash = $flashRepository->findOneBy(["id" => $id], []);

        if($this->getUser())
            $likedFlashs = $flashRepository->likedFLashs($this->getUser()->getId());
        

        // Dans le cas ou l'id pris sur le flash via js est modifié grace à l'inspecteur du navigateur, intval (plus haut) reverra 0
        if($id == 0)
        {
            return $this->render("flash/_flashDialogDetailFlash.html.twig", [
                "likedFlashsId" => $likedFlashs ?: null,
            ]);
        }

        if($flash && $request->get("ajax") == '1')
        {
            $user->addFlash($flash);
            $em->persist($user);
            $em->flush();

            $likedFlashs = $flashRepository->likedFLashs($this->getUser()->getId());

            return $this->render("flash/_flashDialogDetailFlash.html.twig", [
                "likedFlashsId" => $likedFlashs ?: null,
            ]);

        }

        return $this->render("flash/_flashDialogDetailFlash.html.twig", [
            "likedFlashsId" => $likedFlashs ?: null,
        ]);
    }






    #[Route("/member/profile/removeFavProfile", name: "remove_fav_from_profile")]
    #[Route("/member/flash/removeFavFlash", name: "remove_fav_from_flash")]
    public function removeFlashFromFavorites(FlashRepository $flashRepository, Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {

        $user = $this->getUser();
        $id = $request->get("id");

        $id = intval($id);
        $flash = $flashRepository->findOneBy(["id" => $id], []);
        $categories = $categoryRepository->findAll([], []);

        $page = $request->query->getInt("page", 1);
        $flashs = $flashRepository->paginateFlashs($page);
        $maxPages = ceil($flashs->getTotalItemCount() / 8);
        
        if($this->getUser())
            $likedFlashsId = $flashRepository->likedFLashs($this->getUser()->getId());
        

        // Dans le cas ou l'id pris sur le flash via js est modifié grace à l'inspecteur du navigateur, intval (plus haut) reverra 0
        if($id == 0)
        {

            if($request->get("_route") == "remove_fav_from_flash")
            {

                return $this->render("flash/_flashDialogDetailFlash.html.twig", [
                    "likedFlashs" => $likedFlashsId ?: null,

                ]);
            }
            else
            {
                return $this->render('security/_likedFlashContainer.html.twig', [
                    "likedFlashs" => $likedFlashsId,
                    "maxPages" => $maxPages,
                    "categories" => $categories,
                ]);
            }
        }


        if($flash && $request->get("ajax") == '1')
        {
            $user->removeFlash($flash);
            $em->persist($user);
            $em->flush();


            if($request->get("_route") == "remove_fav_from_flash")
            {
                $likedFlashs = $flashRepository->likedFLashs($this->getUser()->getId());
                

                return $this->render("flash/_flashDialogDetailFlash.html.twig", [
                    "likedFlashsId" => $likedFlashs ?: null,
                ]);
            }
            else
            {
                $page = $request->query->getInt("page", 1);

                $likedFlashs = $flashRepository->paginateLikedFlashs($page, $this->getUser()->getId());

                $maxPages = ceil($likedFlashs->getTotalItemCount() / 8);

                return $this->render('security/_likedFlashContainer.html.twig', [
                    "likedFlashs" => $likedFlashs,
                    "maxPages" => $maxPages,
                    "categories" => $categories,
                ]);
            }
        }
    }






    
}