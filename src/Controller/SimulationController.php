<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Size;
use App\Entity\Color;
use App\Entity\Detail;
use App\Form\AreaType;
use App\Form\NameType;
use App\Form\SizeType;
use App\Form\ColorType;
use App\Form\FilesType;
use App\Form\DetailType;
use App\Form\SimulationType;
use App\Repository\AreaRepository;
use App\Repository\SizeRepository;
use App\Repository\ColorRepository;
use App\Repository\DetailRepository;
use App\Repository\TattooRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
        $prixFinalTattoo = null;

        
        
        $formSimu = $this->createForm(SimulationType::class);
        $formSimu->handleRequest($request);
        
        $formName = $this->createForm(NameType::class);
        $formName->handleRequest($request);
        
        $formFiles = $this->createForm(FilesType::class);
        $formFiles->handleRequest($request);
        
        
        
        $formArea = $this->createForm(AreaType::class);
        $formArea->handleRequest($request);
        
        $formColor = $this->createForm(ColorType::class);
        $formColor->handleRequest($request);
        
        $formSize = $this->createForm(SizeType::class);
        $formSize->handleRequest($request);
        
        $formDetail = $this->createForm(DetailType::class);
        $formDetail->handleRequest($request);

        
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


        // Ajout data dans table Size
        if($formSize->isSubmitted() && $formSize->isValid())
        {
            $sizeValue = $formSize->get("size")->getData();
            $multiplicatorSize = $formSize->get("multiplicator")->getData();

            $size = new Size();

            $size->setSize($sizeValue);
            $size->setMultiplicator($multiplicatorSize);

            $em->persist($size);
            $em->flush();

            return $this->redirectToRoute("app_simulation");
        }


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

        // dd($_SESSION);

        // Calcul du prix d'un tatouage grace au formulaire
        if($request->getMethod() == "POST" && $request->get("ajax"))
        {
            dd($request->get("simulation[_token]"));

            dd($this->isCsrfTokenValid('token_id', ));

            $prixBaseTattoo = 93;
            
            dd("test");
            $size = $formSimu->get("size")->getData();
            $color = $formSimu->get("color")->getData();
            $detail = $formSimu->get("detail")->getData();
            $area = $formSimu->get("area")->getData();
            
            $trueSizePlus = $sizeRepository->getClosestSizePlus($size);
            $trueSizeMinus = $sizeRepository->getClosestSizeMinus($size);
            
            dd($trueSizeMinus, $trueSizePlus);
            $diffPlus = $trueSizePlus[0]->getSize() - $size;
            $diffMinus = $size - $trueSizeMinus[0]->getSize();
            // Plus on se rapproche de 0, plus la taille donnée dans le formulaire se rapproche d'une donnée en db 

            // Si diffMinus est plus petit que diffPlus, cela signifie qu'il y a une taille en db qui se rapproche + de diffMinus (qui est en dessous de la taille donnée dans le formulaire)
            if($diffPlus > $diffMinus)
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizeMinus[0]->getMultiplicator() * ($area->getMultiplicator() * $color->getMultiplicator() * $detail->getMultiplicator()));
            // Et inversement, si diffPlus est plus petit que diffMinus, cela signifie qu'il y a une taille en db qui se rapproche + de diffPlus (qui est au dessus de la taille donnée)
            if($diffPlus < $diffMinus)
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizePlus[0]->getMultiplicator() * ($area->getMultiplicator() * $color->getMultiplicator() * $detail->getMultiplicator()));
            else
                $prixFinalTattoo = round($prixBaseTattoo * $trueSizePlus[0]->getMultiplicator() * ($area->getMultiplicator() * $color->getMultiplicator() * $detail->getMultiplicator()));


            return $this->render('simulation/_simuResultContainer.html.twig', [
                'prixFinalTattoo' => $prixFinalTattoo,
            ]);
        }

        
        return $this->render('simulation/index.html.twig', [
            'formSimu' => $formSimu,
            'formSize' => $formSize,
            'formColor' => $formColor,
            'formArea' => $formArea,
            'formDetail' => $formDetail,
            'formName' => $formName,
            'formFiles' => $formFiles,
            'prixFinalTattoo' => $prixFinalTattoo
        ]);
    }
}
