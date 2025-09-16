<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class LanguageController extends AbstractController
{
    #[Route("/change_language/{_locale}", name: "change_language")]
    public function changeLanguage(Request $request, SessionInterface $session, string $_locale): RedirectResponse
    {
        $session->set("_locale", $_locale);

        $referer = $request->headers->get("referer");
        return new RedirectResponse($referer ?: $this->generateUrl("app_accueil"));
    }
}