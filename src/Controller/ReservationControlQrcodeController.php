<?php

namespace App\Controller;

use App\Entity\Reservation;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class ReservationControlQrcodeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/reservation/control/qrcode/{reservation}", name="reservation_control_qrcode")
     */
    public function index($reservation): Response
    {
        $reservationNumber = $reservation;
        $reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy([
            'numberticket' => $reservationNumber
        ]);
        if ($reservation == null) {
            return $this->render('reservation_control_qrcode/error.html.twig', [
                'error' => 'nofind'
            ]);
        }
        $dateChoice = $reservation->getDatechoice()->format('Y-m-d');
        $dateNow = date('Y-m-d');
        $isPaid = $reservation->getIspaid();

        if ($dateChoice == $dateNow && $isPaid) {
            return $this->render('reservation_control_qrcode/success.html.twig', [
                'reservation' => $reservation
            ]);
        }
        elseif ($dateChoice != $dateNow) {
            return $this->render('reservation_control_qrcode/error.html.twig', [
                'error' => 'date',
                'reservation' => $reservation
            ]);
        }
        elseif (!$isPaid) {
            return $this->render('reservation_control_qrcode/error.html.twig', [
                'error' => 'isPaid',
                'reservation' => $reservation
            ]);
        }


       
    }
}
