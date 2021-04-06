<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstname', TextType::class, [//Equivalent a un input
                // Je lui verrouille le type de form (texttype)
                'label' => 'Votre prénom',
                'constraints'=> new Length([
                    'min' => 2,
                    'max' => 30
                ]),
                // Constraint permet de verouiller le nombre de characters
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
                // Je lui rajouter un label pour modifier le text et un placeholder comme en html
            ])

            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])

            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints'=> new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email'
                ]
            ]) 

            ->add('password', RepeatedType::class, [
                
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent etre identique',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe'
                    ]

                ],
                
            ])

            

            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn btn-block btn-success mt-5'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
