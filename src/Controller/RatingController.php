<?php

namespace App\Controller;

use App\Entity\Ratings;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/avis", name="rating")
     */
    public function index(): Response
    {
        $ratings = $this->entityManager->getRepository(Ratings::class)->findAll();
        $sum = 0;

        if (empty($ratings)) {
            return $this->redirectToRoute('home');  
        }else{
            foreach ($ratings as $rating) {
                $sum+=$rating->getScore();
            }
            $roundAverageStar = round($sum / count($ratings));
            $round = round($sum / count($ratings), 2) ;
        }

        return $this->render('rating/index.html.twig',[
            'ratings' => $ratings,
            'roundAverageStar' => $roundAverageStar,
            'round' => $round
        ]);
    }
}
