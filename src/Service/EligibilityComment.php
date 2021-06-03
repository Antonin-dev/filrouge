<?php

namespace App\Service;

use DateTime;


class EligibilityComment
{
    private $reservations;
    private $entityManager;

    public function __construct($reservations)
    {
        $this->reservations = $reservations;

    }


    public function controlEligibility()
    {

        $result = array();
        $dateNow = new DateTime('now');

        foreach ($this->reservations as $reservation) {
            if ($reservation->getDateChoice() < $dateNow && $reservation->getRatings() == null) {
                $result[$reservation->getNumberTicket()] = 'eligible';
            }
            else if ($reservation->getDateChoice() > $dateNow && $reservation->getRatings() == null){
                $result[$reservation->getNumberTicket()] = 'notEligible';
            }
            else{
                $result[$reservation->getNumberTicket()] = 'isSaved';
            }
        }
       
        return $result;
    }
}