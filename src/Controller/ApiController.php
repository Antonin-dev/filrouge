<?php

namespace App\Controller;

use App\Entity\Parc;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/price", name="api_price")
     */
    public function api(): Response
    {
        $parcPrice = $this->entityManager->getRepository(Parc::class)->findOneBy([
            'name' => 'jurassicworld'
        ])->getPrice();
        $data = [
            'price' => $parcPrice / 100
        ];

        return new JsonResponse($data);
    }
}
