<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Service\Mailjet;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        // Vérification 
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

    /**
     * @Route("reinitialisation-mot-de-passe/{token}", name="update_password_token")
     */
    public function update($token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneBy([
            'token' => $token
        ]);
        if ($resetPassword) {
            $this->redirectToRoute('account_reset_password');
        }

        $now = new DateTime();

        if ($now > $resetPassword->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveller.');
            return $this->redirectToRoute('reset_password');
        }
        
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('new_password')->getData();
            $password = $passwordEncoder->encodePassword($resetPassword->getUser(), $newPassword);
            $resetPassword->getUser()->setPassword($password);
            $this->entityManager->flush();
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account_reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
