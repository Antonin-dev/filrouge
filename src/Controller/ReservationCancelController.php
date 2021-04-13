<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationCancelController extends AbstractController
{
    /**
     * @Route("/reservation/cancel", name="reservation_cancel")
     */
    public function index(): Response
    {
        return $this->render('reservation_cancel/index.html.twig', [
            'controller_name' => 'ReservationCancelController',
        ]);
    }
}
