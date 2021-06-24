<?php

namespace App\Controller\Admin;

use App\Entity\Ratings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RatingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ratings::class;
    }


    public function configureActions(Actions $actions): Actions
{
    return $actions
  
        
        ->disable(Action::NEW, Action::EDIT)
    ;
}
    
}
