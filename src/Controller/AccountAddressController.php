<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * @Route("/compte/adresse", name="account_address")
     */
    public function index(): Response
    {
        
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address_add")
     */
    public function add(Request $request): Response
    {
        
        $newAddress = new Address;

        $form = $this->createForm(AddressType::class, $newAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $newAddress->setUser($this->getUser());
            $this->entityManager->persist($newAddress);
            $this->entityManager->flush();

            if ($this->session->get('datechoice') && $this->session->get('quantity')) {
                return $this->redirectToRoute('reservation_address');
            }
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_address_edit")
     */
    public function edit(Request $request, $id): Response
    {

        // Recherche de l'adresse
        $address = $this->entityManager->getRepository(Address::class)->findOneBy([
            'id' => $id
        ]);

        // V??rification
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        // Formulaire
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_address_delete")
     */
    public function remove(Request $request, $id): Response
    {

        // Recherche de l'adresse
        $address = $this->entityManager->getRepository(Address::class)->findOneBy([
            'id' => $id
        ]);
    
        // V??rification
        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('account_address');
        
    }
}
