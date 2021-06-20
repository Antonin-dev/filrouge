<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountUpdatePasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/modifier-mot-de-passe", name="account_update_password")
     */
    public function update(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
    
        $notification = null;
        $alert = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('old_password')->getData();

            // Vérification
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newPassword = $form->get('new_password')->getData();
                $password = $passwordEncoder->encodePassword($user, $newPassword);
                $user->setPassword($password);
                $this->entityManager->flush();
                $notification = "Votre mot de passe à bien été mis à jour";
                $alert = 0;
            }
            else{
                $notification = "Votre mot de passe actuel est pas bon";
                $alert = 1;
            }
        }
        
        return $this->render('account/update_password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'alert' => $alert
        ]);
    }
}
