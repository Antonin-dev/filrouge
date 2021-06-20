<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }
  
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre header'),
            TextareaField::new('content', 'Contenu header'),
            TextField::new('btntitle', 'Titre boutton'),
            TextField::new('btnurl', 'Url bouton'),
            ImageField::new('illustration')
            ->setBasePath('uploads/')
            // indiquer la direction de lecture
            ->setUploadDir('public/uploads/')
            // indique la direction de l'upload
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            // modifie le nom de facon aleatoire pour le fichier
            ->setRequired(false)
        ];
    }
    
}
