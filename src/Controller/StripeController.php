<?php

namespace App\Controller;

use App\Entity\Parc;
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

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * @Route("/reservation/create-session", name="stripe_create_session")
     */
    public function index(): Response
    {

        $entryPrice = $this->entityManager->getRepository(Parc::class)->findOneBy([
            'name' => 'jurassicworld'
        ])->getPrice();

        Stripe::setApiKey('sk_test_51Ifj6fDsg4lIRoSh8dYYR1fdXFpy6Pely5jB0Bf0zxVSOm3d36vacaafbongYeMPiBaKVZm7kq7INNS4NQ3Gk6nJ005vtwBPyU');

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $checkout_session = Session::create([
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
            'quantity' => $this->session->get('quantity'),
        ]],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.html',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        $response = new JsonResponse(['id' => $checkout_session->id]);
        
        return $response;
    }
}
