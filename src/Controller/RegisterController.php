<?php

namespace App\Controller;

use App\Service\Mailjet;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // Création User
        $user = new User;

        // Création formulaire
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        // Création du flag
        $notification = null;

        // Verification et écoute du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // Verification si l'email existe déja en bdd
            $searchEmail = $this->entityManager->getRepository(User::class)->findOneBy([
                'email' => $user->getEmail()
            ]);

            if (!$searchEmail) {
                // hash du mot de passe
                $password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                // Je fige et flush les données
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                // Notification à envoyer
                $notification = 'Votre inscription c\'est correctement déroulée. vous pouvez dès à présent vous connecter à votre compte en cliquant ';             
                // Envoie email
                $mailjet = new Mailjet;
                $mailjet->sendWelcomeEmail($user->getEmail(), $user->getFirstname());

            } else {
                $notification = "L'email que vous avez renseigné existe déja";
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
