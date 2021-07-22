<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'                 => 'First name',
                'attr'                  => ['placeholder'       => 'Your first name']
            ])
            ->add('lastname', TextType::class, [
                'label'                 => 'Last name',
                'attr'                  => ['placeholder'       => 'Your last name']
            ])
            ->add('email', EmailType::class, [
                'label'                 => 'E-mail',
                'attr'                  => ['placeholder'       => 'Your e-mail']
            ])
            ->add('password', RepeatedType::class, [
                'type'                  => PasswordType::class,
                'invalid_message'       => 'Password and confirmation must be the same',
                'required'              => true,
                'first_options'         => [
                    'label'     => 'Password',
                    'attr'      => ['placeholder'       => 'Password']
                ],
                'second_options'         => [
                    'label'     => 'Password Confirmation',
                    'attr'      => ['placeholder'       => 'Password']
                ]
            ])
            ->add('age', NumberType::class, [
                'label'                 => 'Age',
                'attr'                  => ['placeholder'       => 'Your age']
            ])
            ->add('register', SubmitType::class, [
                'label'         => 'Sign up'
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
