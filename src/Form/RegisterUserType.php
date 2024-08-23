<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Email",
                'attr' => [
                    'placeholder' => "Entrez votre adresse mail"
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 30
                    ])
                ],
                'first_options'  => [
                    'label' => 'Mot de Passe',
                    'attr' => [
                        'placeholder' => "Entrez votre Mot de passe"
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez Mot de Passe',
                    'attr' => [
                        'placeholder' => "Confirmez votre Mot de passe"
                    ]
                ],
                'mapped' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                    ])
                ],
                'attr' => [
                    'placeholder' => "Entrez votre Prénom"
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                    ])
                ],
                'attr' => [
                    'placeholder' => "Entrez votre Nom de famille"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => "btn btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email'
                ])
            ],
            'data_class' => User::class,
        ]);
    }
}
