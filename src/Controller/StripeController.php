<?php

namespace App\Controller;

use App\Entity\Parc;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StripeController extends AbstractController
{

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
       
    }

    /**
     * @Route("/reservation/create-session/{ticket}", name="stripe_create_session")
     */
    public function index($ticket): Response
    {

        $entryPrice = $this->entityManager->getRepository(Parc::class)->findOneBy([
            'name' => 'jurassicworld'
        ])->getPrice();

        $reservation = $this->entityManager->getRepository(Reservation::class)->findOneBy([
            'numberticket' => $ticket
        ]);


        if (!$reservation) {
            return new JsonResponse(['error' => 'reservation']);
        }

        Stripe::setApiKey('sk_test_51Ifj6fDsg4lIRoSh8dYYR1fdXFpy6Pely5jB0Bf0zxVSOm3d36vacaafbongYeMPiBaKVZm7kq7INNS4NQ3Gk6nJ005vtwBPyU');

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $checkout_session = Session::create([
        'customer_email' => $reservation->getUser()->getEmail(),
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
            'currency' => 'eur',
            'unit_amount' => $entryPrice,
            'product_data' => [
                'name' => 'Entrée Jurassic World',
                'images' => ["https://upload.wikimedia.org/wikipedia/fr/d/d8/Jurassic_World_Logo.png"],
            ],
            ],
            'quantity' => $reservation->getQuantity(),
        ]],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/reservation/valide/{CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN . '/reservation/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $reservation->setStripesession($checkout_session->id);
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();


        $response = new JsonResponse(['id' => $checkout_session->id]);
        
        return $response;
    }
}
