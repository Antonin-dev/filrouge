<?php

namespace App\Controller\Admin;

use App\Entity\Attraction;
use App\Entity\Category;
use App\Entity\Parc;
use App\Entity\Reservation;
use App\Entity\Spectacle;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Filrouge');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Parc', 'fas fa-store', Parc::class);
        yield MenuItem::linkToCrud('Réservation', 'fas fa-ticket-alt', Reservation::class);
        yield MenuItem::linkToCrud('Attraction', 'fas fa-frog', Attraction::class);
        yield MenuItem::linkToCrud('Spectacle', 'fas fa-democrat', Spectacle::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
    }
}
