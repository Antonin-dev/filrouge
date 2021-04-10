<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccesParcController extends AbstractController
{
    /**
     * @Route("/acces-parc", name="acces_parc")
     */
    public function index(): Response
    {
        return $this->render('acces_parc/index.html.twig');
    }
}
