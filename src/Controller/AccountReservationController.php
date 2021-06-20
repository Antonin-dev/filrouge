<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Service\EligibilityComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountReservationController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/reservation", name="account_reservation")
     */
    public function index(): Response
    {
        $reservations = $this->entityManager->getRepository(Reservation::class)->findBy([
            'user' => $this->getUser()
        ]);


        // Test start
        
        $reservationTrie = $this->entityManager->getRepository(Reservation::class)->findReservationByIspaidAndUser($this->getUser());
        // dd($reservationTrie);
        // Test end
        $eligibilityControle = new EligibilityComment($reservations);
        $eligibilityArray = $eligibilityControle->controlEligibility();


        return $this->render('account/reservation.html.twig', [
            'reservations' => $reservationTrie,
            'commentEligibility' => $eligibilityArray
        ]);
    }
}
