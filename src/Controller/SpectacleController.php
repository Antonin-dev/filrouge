<?php

namespace App\Controller;

use App\Entity\Spectacle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpectacleController extends AbstractController
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/spectacle", name="spectacle")
     */
    public function index(): Response
    {
        $spectacles = $this->entityManager->getRepository(Spectacle::class)->findAll();
      
        return $this->render('spectacle/index.html.twig',[
            'spectacles' => $spectacles
        ]);
    }
}
