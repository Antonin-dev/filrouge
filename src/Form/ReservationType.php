<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('quantity', IntegerType::class,[
                'label' => 'Choisissez le nombre de place',
                'attr' => [
                    'placeholder' => 'Quantité'
                ]
            ])
            ->add('datechoice', DateType::class, [
                'label' => 'Date du séjour',
                'widget' => 'single_text'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Je réserve',
                'attr' => [
                    'class' => 'btn btn-success btn-block mt-2 col-12'
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
