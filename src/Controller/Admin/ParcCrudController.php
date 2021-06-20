<?php

namespace App\Controller\Admin;

use App\Entity\Parc;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ParcCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parc::class;
    }
 
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du parc'),
            IntegerField::new('capacity', 'capacité'),
            MoneyField::new('price', 'Prix entrée')->setCurrency('EUR')
            
        ];
    }
    
}
