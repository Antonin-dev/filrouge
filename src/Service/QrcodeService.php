<?php

namespace App\Service;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QrcodeService
{
    private $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrcode($reservation)
    {
        $url = "https://www.google.fr/search?q=";
        $path = dirname(__DIR__, 2) . '/public/assets/';
        $namepng = uniqid() . '.png';

        // Création du qrcode
        $result = $this->builder
                    ->data($url . $reservation)
                    ->encoding(new Encoding('UTF-8'))
                    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                    ->size(200)
                    ->labelText('Votre réservation')
                    ->logoPath($path . 'img/jurassicqrcode.png')
                    ->logoResizeToHeight('50')
                    ->logoResizeToWidth('50')
                    ->build();

        // Sauvegarde de l'image
        $result->saveToFile($path . 'qr-code/' . $namepng);

        return [
            'qrcode' => $result->getDataUri(),
            'name' => $namepng
        ];

    }


}