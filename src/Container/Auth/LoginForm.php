<?php

namespace App\Container\Auth;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LoginForm extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'required'    => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(min: 3)
                ]
            ])
            ->add('password', TextType::class, [
                'required'    => false,
                'use_flash_field' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(min: 3)
                ]
            ])
            ->add('submit', SubmitType::class);
    }
}