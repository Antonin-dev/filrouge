<?php

namespace App\Controller\Admin;

use App\Entity\Spectacle;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SpectacleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Spectacle::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('horaire', 'Horaire'),
            TextField::new('subtitle', 'Sous-titre'),
            TextareaField::new('description', 'Description'),
            ImageField::new('picture', 'Image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
           
        ];
    }
    
}
