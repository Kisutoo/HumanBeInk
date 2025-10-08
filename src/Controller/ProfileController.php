<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NicknameType;
use App\Repository\UserRepository;
use App\Repository\FlashRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route(path: '/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager, )
    {
        $user = $this->getUser();

        $form = $this->createForm(NicknameType::class);
        $form->handleRequest($request);
        if($user)
        {   
            $likedFlashs = $user->getFlashs();


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
                "logoNom" => 1,
                "footer" => 0,
            ]);
        }
        else
        {
            $this->addFlash("error", "Vous devez vous connecter.");
            return $this->redirectToRoute("app_login");
        } 
    }


    #[Route(path: "/delete", name: "app_delete_profile")]
    public function deleteProfile(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if($user)
        {
            $this->container->get('security.token_storage')->setToken(null);

            $entityManager->remove($user);
            $entityManager->flush();
        
            $this->addFlash("success", "Votre compte a été supprimé avec succès.");
            return $this->redirectToRoute("app_accueil");
        }
        else
        {
            $this->addFlash("error", "Personne n'est connecté.");
            return $this->redirectToRoute("app_accueil"); 
        }

    }

    #[Route("flash/addFav", name: "add_fav")]
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
                "likedFlashs" => $likedFlashs ?: null,
            ]);
        }

        if($flash && $request->get("ajax") == '1')
        {
            $user->addFlash($flash);
            $em->persist($user);
            $em->flush();

            $likedFlashs = $flashRepository->likedFLashs($this->getUser()->getId());

            return $this->render("flash/_flashDialogDetailFlash.html.twig", [
                "likedFlashs" => $likedFlashs ?: null,
            ]);

        }

        return $this->render("flash/_flashDialogDetailFlash.html.twig", [
            "likedFlashs" => $likedFlashs ?: null,
        ]);
    }







    #[Route("flash/removeFav", name: "remove_fav")]
    public function removeFlashFromFavorites(FlashRepository $flashRepository, Request $request, EntityManagerInterface $em)
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
                "likedFlashs" => $likedFlashs ?: null,
            ]);
        }
        if($flash && $request->get("ajax") == '1')
        {
            $user->removeFlash($flash);
            $em->persist($user);
            $em->flush();

            $likedFlashs = $flashRepository->likedFLashs($this->getUser()->getId());

            return $this->render("flash/_flashDialogDetailFlash.html.twig", [
                "likedFlashs" => $likedFlashs ?: null,
            ]);

        }

        return $this->render("flash/_flashDialogDetailFlash.html.twig", [
            "likedFlashs" => $likedFlashs ?: null,
        ]);
    }
}