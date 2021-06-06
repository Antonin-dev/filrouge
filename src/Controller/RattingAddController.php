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
     * @Route("/avis/ajouter/{id}", name="ratting_add")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $rating = new Ratings;
        
        $form = $this->createForm(RatingsType::class, $rating);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'id' => $id
            ]);
            
            $rating->setDatecomment(new DateTime('now'));
            $rating->setReservationname(ucfirst(strtolower($this->getUser()->getFirstname())));
            $rating->setReservation($reservation);
            // dd($rating);
            $entityManager->persist($rating);
            $entityManager->flush();

            
            return $this->redirectToRoute('account_reservation');
        }

        

        return $this->render('rating/rating_add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
