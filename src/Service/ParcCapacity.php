<?php

namespace App\Service;

use App\Entity\Parc;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

class ParcCapacity
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function CapacityControl($dateChoice, $quantity)
    {
        $dayReservations = $quantity;

        $capacity = $this->entityManager->getRepository(Parc::class)->findOneBy([
            'name' => 'jurassicworld'
        ])->getCapacity();

        $arrayReservations = $this->entityManager->getRepository(Reservation::class)->findBy([
            'datechoice' => $dateChoice
        ]);

        foreach ($arrayReservations as $reservation) {
            $dayReservations += $reservation->getQuantity();
        }

        if ($dayReservations < $capacity) {
            return true;
        }else{
            return false;
        }
    }
}