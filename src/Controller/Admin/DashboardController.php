<?php

namespace App\Controller\Admin;

use App\Entity\Parc;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Spectacle;
use App\Entity\Attraction;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ReservationCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jurassic World');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Parc', 'fas fa-store', Parc::class);
        yield MenuItem::linkToCrud('Réservations', 'fas fa-ticket-alt', Reservation::class);
        yield MenuItem::linkToCrud('Attractions', 'fas fa-frog', Attraction::class);
        yield MenuItem::linkToCrud('Spectacles', 'fas fa-democrat', Spectacle::class);
        yield MenuItem::linkToCrud('Categorie', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
    }
}
