<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Service\Mailjet;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountResetPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/reinitialisation-mot-de-passe", name="account_reset_password")
     */
    public function index(Request $request): Response
    {
        
        if ($this->getUser()) {
            return $this->redirectToRoute('home');      
        }

        if ($request->get('email')) {
            $userResetPassword = $this->entityManager->getRepository(User::class)->findOneBy([
                'email' => $request->get('email')
            ]);

            if ($userResetPassword) {
                $resetPassword = new ResetPassword;
                $resetPassword->setUser($userResetPassword);
                $resetPassword->setCreatedAt(new DateTime());
                $resetPassword->setToken(uniqid()); 
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();
                $slug = "reinitialisation-mot-de-passe/" . $resetPassword->getToken();
                // dd($slug);

                $url = '<a href="' . $_ENV['DOMAIN_URL'] . $slug . '">mettre à jour votre mot de passe</a>';
                // dd($url);
                $mail = new Mailjet();
                $mail->resetPassword($userResetPassword->getEmail(), $userResetPassword->getFirstName(), $url);
                $this->addFlash('notice','Un email vient de vous etes envoyé a l\'adresse indiqué .');

            }else{
                $this->addFlash('notice', 'L\'adresse émail que vous avez renseigné est non reconnue');
            }

        }

        return $this->render('account_reset_password/index.html.twig');
    }
}
