<?php

declare(strict_types=1);

namespace App\Ship\Parent;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class FormType extends AbstractType
{
    public function __construct(
        #[Autowire('%csrf_token_id%')]
        protected string $csrfTokenId
    ) {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('csrf_token_id', $this->csrfTokenId);
    }
}
