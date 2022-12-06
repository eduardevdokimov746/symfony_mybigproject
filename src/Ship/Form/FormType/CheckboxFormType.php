<?php

declare(strict_types=1);

namespace App\Ship\Form\FormType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckboxFormType extends CheckboxType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('label_attr', ['class' => 'checkbox-inline']);
    }
}
