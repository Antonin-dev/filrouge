<?php

namespace App\Form;

use App\Entity\Ratings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RatingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('score', IntegerType::class, [
                'label' => 'Indiquez votre évaluation entre 1 et 5',
                'attr' => [
                    'min' => 1,
                    'max' => 5
                ]
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Saisissez votre commentaire',
                'attr' => [
                    'placeholder' => 'Votre commentaire'
                ],
                'constraints' => new Length([
                    'min' => 5,
                    'max' => 300
                ])
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour",
                'attr' => [
                    'class' => 'btn btn-success mt-2'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ratings::class,
        ]);
    }
}
