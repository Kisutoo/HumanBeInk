<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Size;
use App\Entity\Color;
use App\Entity\Detail;
use App\Entity\Tattoo;
use App\Form\AreaType;
use App\Form\SizeType;
use App\Form\ColorType;
use App\Form\DetailType;
use App\Form\SaveSimuType;
use App\Form\SimulationType;
use App\Repository\AreaRepository;
use App\Repository\SizeRepository;
use App\Repository\ColorRepository;
use App\Service\ConvertImageFormat;
use App\Repository\DetailRepository;
use App\Repository\TattooRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SimulationController extends AbstractController
{
    #[Route('/simulation', name: 'app_simulation')]
    public function index(Request $request,
        EntityManagerInterface $em,
        DetailRepository $detailRepository,
        ColorRepository $colorRepository,
        AreaRepository $areaRepository,
        SizeRepository $sizeRepository,
        TattooRepository $tattooRepository
        ): Response
        {   

        $prixBaseTattoo = 93;
        $prixFinalTattoo = null;

        
        $formSimu = $this->createForm(SimulationType::class);
        $formSimu->handleRequest($request);
        
        $formSaveSimu = $this->createForm(SaveSimuType::class);
        $formSaveSimu->handleRequest($request);
        
        
        $formArea = $this->createForm(AreaType::class);
        $formArea->handleRequest($request);
        
        $formColor = $this->createForm(ColorType::class);
        $formColor->handleRequest($request);
        
        $formSize = $this->createForm(SizeType::class);
        $formSize->handleRequest($request);
        
        $formDetail = $this->createForm(DetailType::class);
        $formDetail->handleRequest($request);



        // Calcul du prix d'un tatouage grace au formulaire
        if($request->getMethod() == "POST" && $request->get("ajax"))
        {
            $session = new Session();
            $session->set("simulation", array());

            $allData = $session->get("simulation");
            $allData["prixBase"] = $prixBaseTattoo;
            $session->set("simulation", $allData);


            $size = floatval($request->request->all()["simulation"]["size"]);
            // On s'assure que la valeur qu'on récupère du formulaire est bien un float (décimal)
            $color = intval($request->request->all()["simulation"]["color"]);
            // On s'assure que la valeur qu'on récupère du formulaire est bien un int (entier)
            $area = intval($request->request->all()["simulation"]["area"]);
            $detail = intval($request->request->all()["simulation"]["detail"]);

            
            $colorObj = $colorRepository->findOneBy(["id" => $color], []);
            // Puis on vient chercher en BD l'enregistrement dont l'id correspond à la couleur séléctionnée
            // dans le formulaire
            $areaObj = $areaRepository->findOneBy(["id" => $area], []);
            
            $detailObj = $detailRepository->findOneBy(["id" => $detail], []);
            
            if($colorObj)
            {
                $allData = $session->get("simulation");
                $allData["color"] = $color;
                $session->set("simulation", $allData);
            }

            if($detailObj)
            {
                $allData = $session->get("simulation");
                $allData["detail"] = $detail;
                $session->set("simulation", $allData);
            }

            if($areaObj)
            {
                $allData = $session->get("simulation");
                $allData["area"] = $area;
                $session->set("simulation", $allData);
            }


            // Récupère la taille la plus proche en base de données
            $trueSizePlus = $sizeRepository->getClosestSizePlus($size);
            // Récupère la taille la plus proche en base de données
            $trueSizeMinus = $sizeRepository->getClosestSizeMinus($size);
            

            $diffPlus = $trueSizePlus ? $trueSizePlus[0]->getSize() - $size : null;
            $diffMinus = $trueSizeMinus ? $size - $trueSizeMinus[0]->getSize() : null;
            // Plus on se rapproche de 0, plus la taille donnée dans le formulaire se rapproche d'une donnée en db 


            if($diffMinus == null)
            {
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizePlus[0]->getMultiplicator() * 
                ($areaObj->getMultiplicator() * $colorObj->getMultiplicator() * $detailObj->getMultiplicator()));
                // On stocke le résultat du calcul dans prixFinalTattoo. Le résultat est arrondi grace à la fonction round()

                $allData = $session->get("simulation");
                $allData["size"] = $trueSizePlus[0]->getId();
                $allData["finalPrice"] = $prixFinalTattoo;
                $session->set("simulation", $allData);
                // Puis on stocke les différentes variables dans la session afin de les réutiliser lors de la sauvegarde de la simulation
            }

            elseif($diffPlus == null)
            {
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizeMinus[0]->getMultiplicator() * 
                ($areaObj->getMultiplicator() * $colorObj->getMultiplicator() * $detailObj->getMultiplicator()));
                // On stocke le résultat du calcul dans prixFinalTattoo. Le résultat est arrondi grace à la fonction round()

                $allData = $session->get("simulation");
                $allData["size"] = $trueSizeMinus[0]->getId();
                $allData["finalPrice"] = $prixFinalTattoo;
                $session->set("simulation", $allData);
                // Puis on stocke les différentes variables dans la session afin de les réutiliser lors de la sauvegarde de la simulation
            }

            // Si diffMinus est plus petit que diffPlus, cela signifie qu'il y a une taille en db qui se rapproche 
            // plus de diffMinus (qui est en dessous de la taille donnée dans le formulaire)
            elseif($diffPlus > $diffMinus)
            {
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizeMinus[0]->getMultiplicator() * 
                ($areaObj->getMultiplicator() * $colorObj->getMultiplicator() * $detailObj->getMultiplicator()));
                // On stocke le résultat du calcul dans prixFinalTattoo. Le résultat est arrondi grace à la fonction round()
                
                $allData = $session->get("simulation");
                $allData["size"] = $trueSizeMinus[0]->getId();
                $allData["finalPrice"] = $prixFinalTattoo;
                $session->set("simulation", $allData);
                // Puis on stocke les différentes variables dans la session afin de les réutiliser lors de la sauvegarde de la simulation
            }
            
            // Et inversement, si diffPlus est plus petit que diffMinus, cela signifie qu'il y a une taille en db qui se rapproche + de diffPlus (qui est au dessus de la taille donnée). On utilisera donc diffPlus au lieu de diffMinus
            elseif($diffPlus < $diffMinus)
            {
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizePlus[0]->getMultiplicator() * ($areaObj->getMultiplicator() * $colorObj->getMultiplicator() * $detailObj->getMultiplicator()));
                
                $allData = $session->get("simulation");
                $allData["size"] = $trueSizePlus[0]->getId();
                $allData["finalPrice"] = $prixFinalTattoo;
                $session->set("simulation", $allData);
            }
            else
            // Dans le cas ou diffPlus et diffMinus sont égaux
            {
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizePlus[0]->getMultiplicator() * ($areaObj->getMultiplicator() * $colorObj->getMultiplicator() * $detailObj->getMultiplicator()));
                
                $allData = $session->get("simulation");
                $allData["size"] = $trueSizePlus[0]->getId();
                $allData["finalPrice"] = $prixFinalTattoo;
                $session->set("simulation", $allData);
            }

            if($size == 0)
            {
                $prixFinalTattoo = 0;
                $allData = $session->get("simulation");
                $allData["finalPrice"] = $prixFinalTattoo;
                $session->set("simulation", $allData);
            }
            

            return $this->render('simulation/_simuResultContainer.html.twig', [
            // On renvoie un fragment twig avec le prix final du tatouage calculé préalablement
                'prixFinalTattoo' => $prixFinalTattoo,
                'formSaveSimu' => $formSaveSimu,
            ]);
        }

        // Vue retournée sans action, en accèdant à la page
        return $this->render('simulation/index.html.twig', [
            'formSimu' => $formSimu,
            'formSize' => $formSize,
            'formColor' => $formColor,
            'formArea' => $formArea,
            'formDetail' => $formDetail,
            'formSaveSimu' => $formSaveSimu,
            'prixFinalTattoo' => $prixFinalTattoo,
            "logoNom" => 1,
            "footer" => 1,
        ]);
    }



    #[Route('/member/simulation/saveSimulation', name: 'save_simu')]
    public function saveSimulation(Request $request, 
        EntityManagerInterface $em,         
        DetailRepository $detailRepository,
        ColorRepository $colorRepository,
        AreaRepository $areaRepository,
        SizeRepository $sizeRepository)
    {
        $formSaveSimu = $this->createForm(SaveSimuType::class);
        $formSaveSimu->handleRequest($request);

        if($formSaveSimu->isSubmitted() && $formSaveSimu->isValid() && $request->getMethod() == "POST" && isset($_SESSION["_sf2_attributes"]["simulation"]))
        {
            $tattoo = new Tattoo();
            $imageConverter = new ConvertImageFormat();


            $files = $_FILES["save_simu"];

            $tattoo->setName($formSaveSimu->get("name")->getData());
            $tattoo->setBasePrice($_SESSION["_sf2_attributes"]["simulation"]["prixBase"]);
            $tattoo->setSize($sizeRepository->findOneBy(["id" => $_SESSION["_sf2_attributes"]["simulation"]["size"]]));
            $tattoo->setColor($colorRepository->findOneBy(["id" => $_SESSION["_sf2_attributes"]["simulation"]["color"]]));
            $tattoo->setArea($areaRepository->findOneBy(["id" => $_SESSION["_sf2_attributes"]["simulation"]["area"]]));
            $tattoo->setDetail($detailRepository->findOneBy(["id" => $_SESSION["_sf2_attributes"]["simulation"]["detail"]]));
            $tattoo->setFinalPrice($_SESSION["_sf2_attributes"]["simulation"]["finalPrice"]);
            $tattoo->setUser($this->getUser());

            // On récupère toutes les variables présentes en session puis on vient hydrater l'entité Tattoo grace à elles
            
            $imageConverter->convertImageToWebp($files, null, $tattoo);
            // On utilise le service de conversion d'image afin de s'assurer que celle ci soit bien aux normes puis on l'ajoute dans l'entité Tattoo
            // nb : Le service s'occupe d'hydrater l'entité fournie, c'est pour celà qu'on ne voit pas de "$tattoo->setImage(......)" plus haut

            $em->persist($tattoo);
            // L'EntityManager prépare la requête grace à persist()
            $em->flush();
            // Puis on l'execute grace à flush(). L'utilisateur possède désormais une simulation de tatouage

            $this->addFlash("success", "Votre simulation a bien été ajouté sur votre profil !");
            return $this->redirectToRoute("app_simulation");
            // Puis on renvoie l'utilisateur sur la page de simulation avec un message de succès
        }
        

        return $this->redirectToRoute("app_simulation");
    }









    #[Route('/admin/simulation/addSize', name: 'add_size')]
    public function addSize(Request $request, EntityManagerInterface $em)
    {  

        $formSize = $this->createForm(SizeType::class);
        $formSize->handleRequest($request);

        // Ajout data dans table Size
        if($formSize->isSubmitted() && $formSize->isValid())
        {
            $sizeValue = $formSize->get("size")->getData();
            $multiplicatorSize = $formSize->get("multiplicator")->getData();

            $size = new Size();
            // Création d'un nouvel objet size

            $size->setSize($sizeValue);
            $size->setMultiplicator($multiplicatorSize);
            // On récupère les valeurs reçues du formulaire pour les attribuer aux propriété de l'objet fraichement instancié

            $em->persist($size);
            $em->flush(); // Correspond à "INSERT INTO size ..."

            return $this->redirectToRoute("app_simulation");
        }

        return $this->redirectToRoute("app_simulation");
    }





    #[Route('admin/simulation/addColor', name: 'add_color')]
    public function addColor(Request $request, EntityManagerInterface $em)
    {
        
        $formColor = $this->createForm(ColorType::class);
        $formColor->handleRequest($request);


        // Ajout data dans table Color
        if($formColor->isSubmitted() && $formColor->isValid())
        {
            $colorType = $formColor->get("colorType")->getData();
            $multiplicatorColor = $formColor->get("multiplicator")->getData();

            $color = new Color();

            $color->setTypeColor($colorType);
            $color->setMultiplicator($multiplicatorColor);

            $em->persist($color);
            $em->flush();

            return $this->redirectToRoute("app_simulation");
        }

        return $this->redirectToRoute("app_simulation");
    }



    


    #[Route('admin/simulation/addDetail', name: 'add_detail')]
    public function addDetail(Request $request, EntityManagerInterface $em)
    {
        
        $formDetail = $this->createForm(DetailType::class);
        $formDetail->handleRequest($request);


        // Ajout data dans table Detail
        if($formDetail->isSubmitted() && $formDetail->isValid())
        {
            $detailType = $formDetail->get("detailName")->getData();
            $multiplicatorDetail = $formDetail->get("multiplicator")->getData();

            $detail = new Detail();

            $detail->setDetailName($detailType);
            $detail->setMultiplicator($multiplicatorDetail);

            $em->persist($detail);
            $em->flush();

            return $this->redirectToRoute("app_simulation");
        }

        return $this->redirectToRoute("app_simulation");
    }





    #[Route('admin/simulation/addArea', name: 'add_area')]
    public function addArea(Request $request, EntityManagerInterface $em)
    {
        $formArea = $this->createForm(AreaType::class);
        $formArea->handleRequest($request);

        // Ajout data dans table Area
        if($formArea->isSubmitted() && $formArea->isValid())
        {
            $areaName = $formArea->get("areaName")->getData();
            $multiplicatorArea = $formArea->get("multiplicator")->getData();
            $sensibility = $formArea->get("sensibility")->getData();

            $area = new Area();

            $area->setNameArea($areaName);
            $area->setMultiplicator($multiplicatorArea);

            if($sensibility == true)
                $area->setSensibility(1);
            else
                $area->setSensibility(0);

            $em->persist($area);
            $em->flush();
            
            return $this->redirectToRoute("app_simulation");
        }

        return $this->redirectToRoute("app_simulation");
    }
}