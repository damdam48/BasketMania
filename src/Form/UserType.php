<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Administrator' => 'ROLE_ADMIN',
            //         'User' => 'ROLE_USER',
            //     ],
            //     'expanded' => true,
            //     'multiple' => true,
            // ])
            ->add('password',  RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => "S3CR3T",
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer',
                    'attr' => [
                        'placeholder' => "S3CR3T",
                    ]
                ],
            ])
            ->add('firstName')
            ->add('lastName');
        // ->add('telephone')
        // ->add('birthDate', null, [
        //     'widget' => 'single_text',
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
