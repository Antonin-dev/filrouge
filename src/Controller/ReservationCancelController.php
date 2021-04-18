<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationCancelController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/reservation/erreur/{stripeId}", name="reservation_cancel")
     */
    public function index($stripeId): Response
    {

        $reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy([
            'stripesession' => $stripeId
        ]);

        if (!$reservation || $reservation->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('reservation_cancel/index.html.twig');
    }
}
