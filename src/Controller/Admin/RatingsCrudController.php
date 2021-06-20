<?php

namespace App\Controller\Admin;

use App\Entity\Ratings;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RatingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ratings::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
