<?php

namespace App\Controller;

use App\Entity\Area;
use App\Entity\Size;
use App\Entity\Color;
use App\Entity\Detail;
use App\Form\AreaType;
use App\Form\SizeType;
use App\Form\ColorType;
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
        $formSimu = $this->createForm(SimulationType::class);
        $formSimu->handleRequest($request);
        
        $formArea = $this->createForm(AreaType::class);
        $formArea->handleRequest($request);

        $formColor = $this->createForm(ColorType::class);
        $formColor->handleRequest($request);

        $formSize = $this->createForm(SizeType::class);
        $formSize->handleRequest($request);

        $formDetail = $this->createForm(DetailType::class);
        $formDetail->handleRequest($request);

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

            $em->persist($area);
            $em->flush();
            
            return $this->redirectToRoute("app_simulation");
        }
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

        
        return $this->render('simulation/index.html.twig', [
            'formSimu' => $formSimu,
            'formSize' => $formSize,
            'formColor' => $formColor,
            'formArea' => $formArea,
            'formDetail' => $formDetail
        ]);
    }
}
