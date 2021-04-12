<?php

namespace App\Controller\Admin;

use App\Entity\Attraction;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class AttractionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Attraction::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('picture', 'Miniature')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            ImageField::new('imagefull', 'Image Full')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->hideOnIndex(),
            AssociationField::new('category', 'Catégorie'),
            TextField::new('subtitle', 'Sous-titre'),
            TextareaField::new('description', 'Description'),
            BooleanField::new('isBest', 'Mise en avant')

            
        ];
    }
    
}
