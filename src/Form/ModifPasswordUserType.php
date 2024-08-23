<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ModifPasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class,[
                'label' => 'Votre Mot de Passe actuel',
                'attr' => [
                    'placeholder' => 'Saisissez vot Mot de Passe actuel'
                ],
                'mapped' => false,
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
                    'label' => 'Votre nouveau Mot de Passe',
                    'attr' => [
                        'placeholder' => "Saisissez votre nouveau Mot de passe"
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau Mot de Passe',
                    'attr' => [
                        'placeholder' => "Confirmez votre nouveau Mot de passe"
                    ]
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour votre Mot de Passe",
                'attr' => [
                    'class' => "btn btn-success"
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];

                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                //1. Récupération du MDP saisi par l'utilisateur
                //$actualPwd = $form->get('actualPassword')->getData();
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

                //2. Récupération du MDP depuis la BDD
                //$actualPwdDatabase = $user->getPassword();

                //3. Si c'est différent, envoyer une erreur
                if(!$isValid){
                    $form->get('actualPassword')->addError(new FormError("Votre MDP actuel n'est pas conforme. Vérifiez-le !"));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
