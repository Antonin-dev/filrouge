<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('numberticket', 'Numero réservation'),
            DateField::new('datechoice', 'Date séjour'),
            BooleanField::new('ispaid', 'Payé'),
            AssociationField::new('user', 'utilisateur'),
            DateTimeField::new('createdat', 'crée le'),
            IntegerField::new('quantity', 'Quantité'),
            MoneyField::new('total')->setCurrency('EUR')
        ];
    }
    
}
