<?php

declare(strict_types=1);

namespace App\Ship\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @implements DataTransformerInterface<mixed, mixed>
 */
class DefaultValueDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private FormBuilderInterface $builder,
        private mixed $defaultValue
    ) {
    }

    public function transform(mixed $value): mixed
    {
        return null !== $this->builder->getData() ? $value : $this->defaultValue;
    }

    public function reverseTransform(mixed $value): mixed
    {
        return $value;
    }
}
