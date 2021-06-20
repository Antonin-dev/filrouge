<?php

namespace App\Controller;

use App\Entity\Ratings;
use App\Entity\Reservation;
use App\Form\RatingsType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RattingAddController extends AbstractController
{

    /**
     * @Route("/avis/ajouter/{numberticket}", name="ratting_add")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, $numberticket): Response
    {
        $rating = new Ratings;
        $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'numberticket' => $numberticket
            ]);
        $notification = null;
        $form = $this->createForm(RatingsType::class, $rating);
        $form->handleRequest($request);

        // Sécurité
        if ($reservation == null) {
            return $this->redirectToRoute('account');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setDatecomment(new DateTime('now'));
            $rating->setReservationname(ucfirst(strtolower($this->getUser()->getFirstname())));
            $rating->setReservation($reservation);
            // dd($rating);
            $entityManager->persist($rating);
            $entityManager->flush();
            $notification = 'Commentaire ajouté avec succes, merci pour votre retour.';

            
        
        }

        

        return $this->render('rating/rating_add.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
