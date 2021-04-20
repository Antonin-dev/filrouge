<?php

namespace App\Controller;

use App\Entity\Parc;
use App\Service\Mailjet;
use App\Entity\Reservation;
use App\Service\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/reservation/valide/{stripeId}", name="reservation_success")
     */
    public function index($stripeId, QrcodeService $qrcode): Response
    {
        $price = $this->entityManager->getRepository(Parc::class)->findOneBy([
            'name' => 'jurassicworld'
        ])->getPrice();

        $reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy([
            'stripesession' => $stripeId
        ]);

        if (!$reservation || $reservation->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if (!$reservation->getIsPaid()) {
            $reservation->setIsPaid(true);
            $imgQrCode = $qrcode->qrcode($reservation->getNumberticket());
            $reservation->setQrcode($imgQrCode['name']);
            $this->entityManager->flush();
        }
        
        
        $mailjet = new Mailjet;
        $mailjet->reservationEmail($this->getUser()->getEmail(), $this->getUser()->getFirstName(), $reservation->getNumberticket(), date_format($reservation->getDatechoice(), 'd/m/Y'), $reservation->getQuantity(), $reservation->getQrcode());

  

        return $this->render('reservation_success/index.html.twig', [
            'reservation' => $reservation,
            'price' => $price,
            
        ]);
    }
}
