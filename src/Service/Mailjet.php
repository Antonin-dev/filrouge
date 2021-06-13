<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class Mailjet
{
    private $apiKey = '694b656cac04044e5730ca9c68774e39';
    private $apiKeySecret = 'a0345162a44c19c78221e119b16548eb';


    public function sendWelcomeEmail($email, $name)
    {
        $mj = new Client($this->apiKey, $this->apiKeySecret, true, ['version' => 'v3.1']);

        $body = [
          'Messages' => [
            [
              'From' => [
                'Email' => "jurassicworld.adrar@gmail.com",
                'Name' => "Jurassic World"
              ],
              'To' => [
                [
                  'Email' => $email,
                  'Name' => $name
                ]
              ],
              'TemplateID' => 2796655,
              'TemplateLanguage' => true,
              'Subject' => "bienvenue",
              'Variables' => [
                "name" => $name
              ]
            ]
          ]
        ];
          $response = $mj->post(Resources::$Email, ['body' => $body]);
          $response->success();
    }


    public function reservationEmail($email, $name, $numberResa, $date, $quantity, $qrcode)
    {
        $mj = new Client($this->apiKey, $this->apiKeySecret, true, ['version' => 'v3.1']);
        $qrcodeUrl = $_ENV['DOMAIN_URL'] . 'assets/qr-code/' . $qrcode;

        $body = [
          'Messages' => [
            [
              'From' => [
                'Email' => "jurassicworld.adrar@gmail.com",
                'Name' => "Jurassic World"
              ],
              'To' => [
                [
                  'Email' => $email,
                  'Name' => $name
                ]
              ],
              'TemplateID' => 2800658,
              'TemplateLanguage' => true,
              'Subject' => "bienvenue",
              'Variables' => [
                "name" => $name,
                "numberresa" => $numberResa,
                "date" => $date,
                "quantity" => $quantity,
                "qrcode" => '<img src="' . $qrcodeUrl . '"></img>'
              ]
            ]
          ]
        ];
          $response = $mj->post(Resources::$Email, ['body' => $body]);
          $response->success();
    }


    public function resetPassword($email, $name, $link)
    {
        $mj = new Client($this->apiKey, $this->apiKeySecret, true, ['version' => 'v3.1']);

        $body = [
          'Messages' => [
            [
              'From' => [
                'Email' => "jurassicworld.adrar@gmail.com",
                'Name' => "Jurassic World"
              ],
              'To' => [
                [
                  'Email' => $email,
                  'Name' => $name
                ]
              ],
              'TemplateID' => 2796744,
              'TemplateLanguage' => true,
              'Subject' => "bienvenue",
              'Variables' => [
                "name" => $name,
                "lien" => $link
              ]
            ]
          ]
        ];
          $response = $mj->post(Resources::$Email, ['body' => $body]);
          $response->success();
    }
}

