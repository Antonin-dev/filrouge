<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Entity\Parc;
use App\Entity\Address;
use App\Service\Mailjet;
use App\Entity\Reservation;
use Stripe\Checkout\Session;
use App\Form\ReservationType;
use App\Service\ParcCapacity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
    public function index(Request $request, ParcCapacity $parcCapacity): Response
    {
        $notification = null;
        $date = new DateTime();
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('datechoice')->getData() > $date) {

                $resultCapacity = $parcCapacity->CapacityControl($form->get('datechoice')->getData(), $form->get('quantity')->getData());
                
                if ($resultCapacity) {
                    $this->session->set('datechoice', $form->get('datechoice')->getData());
                    $this->session->set('quantity', $form->get('quantity')->getData());
                    
                    if (!$this->getUser()->getAddresses()->getValues()) {

                        return $this->redirectToRoute('account_address_add');
                    
                    }
                    
                    return $this->redirectToRoute('reservation_address'); 
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
     * @Route("/reservation/choix-adresse", name="reservation_address")
     */
    public function addressChoice(): Response
    {

        return $this->render('reservation/address_choice.html.twig');
    }





    /**
     * @Route("/reservation/recap/{addressid}", name="reservation_recap")
     */
    public function recap($addressid): Response
    {
        $date = new DateTime();
        $entryPrice = $this->entityManager->getRepository(Parc::class)->findOneBy(['name' => 'jurassicworld'])->getPrice();
        $addressReservation = $this->entityManager->getRepository(Address::class)->findOneBy([
            'id' => $addressid
        ]);

        $reservation = new Reservation;
        $reservation->setDatechoice($this->session->get('datechoice'));
        $reservation->setQuantity($this->session->get('quantity'));
        $reservation->setNumberticket($date->format('dmY').'-'.uniqid());
        $reservation->setUser($this->getUser());
        $reservation->setIspaid(false);
        $reservation->setCreatedat($date);
        $reservation->setAddressReservation($addressReservation);
        $reservation->setTotal($entryPrice * $this->session->get('quantity'));

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $this->render('reservation/recap.html.twig',[
            'reservation' => $reservation,
            'price' => $entryPrice
        ]);
    }
}
