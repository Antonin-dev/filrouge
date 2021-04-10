<?php

namespace App\Controller;

use App\Classe\Mailjet;
use App\Entity\Parc;
use App\Entity\Reservation;
use App\Form\ReservationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class ReservationController extends AbstractController
{

    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(Request $request): Response
    {
        $notification = null;
        $date = new DateTime();
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('datechoice')->getData() > $date) {

                $reservationJour = $form->get('quantity')->getData();

                $capacityParc = $this->entityManager->getRepository(Parc::class)->findOneBy([
                    'name' => 'jurassicpark'
                ])->getCapacity();
                $reservations = $this->entityManager->getRepository(Reservation::class)->findBy([
                    'datechoice' => $form->get('datechoice')->getData()
                ]);

                foreach ($reservations as $reservation) {
                    $reservationJour += $reservation->getQuantity();
                }

                if ($reservationJour < $capacityParc) {
                    $this->session->set('datechoice', $form->get('datechoice')->getData());
                    $this->session->set('quantity', $form->get('quantity')->getData());
                    // dd($this->session->all());
                    return $this->redirectToRoute('reservation_recap'); 
                }
                else{
                    $notification = "Capacité maximum du parc atteint veuillez choisir une autre date";
                }
                
            }
            else{
                $notification = "Vous ne pouvez choisir une date antérieur à la date du jour.";
            }

            
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }



    /**
     * @Route("/reservation/recap", name="reservation_recap")
     */
    public function recap(): Response
    {
        $date = new DateTime();
        $entryPrice = $this->entityManager->getRepository(Parc::class)->findOneBy(['name' => 'jurassicpark'])->getPrice();
        
        $reservation = new Reservation;
        $reservation->setDatechoice($this->session->get('datechoice'));
        $reservation->setQuantity($this->session->get('quantity'));
        $reservation->setNumberticket($date->format('dmY').'-'.uniqid());
        $reservation->setUser($this->getUser());
        $reservation->setIspaid(false);
        $reservation->setCreatedat($date);
        $reservation->setTotal($entryPrice * $this->session->get('quantity'));

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

    

        $mailjet = new Mailjet;
        $mailjet->reservationEmail($this->getUser()->getEmail(), $this->getUser()->getFirstName(), $reservation->getNumberticket(), date_format($reservation->getDatechoice(), 'd/m/Y'), $reservation->getQuantity());
        


        return $this->render('reservation/recap.html.twig',[
            'reservation' => $reservation,
            'price' => $entryPrice
        ]);
    }
}
