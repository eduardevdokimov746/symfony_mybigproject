<?php

namespace App\Ship\Parent;

use App\Ship\Contract\Validator;
use OutOfBoundsException;
use ReflectionClass;
use ReflectionProperty;

abstract class DTO
{
    public static function fromValidator(Validator $validator): static
    {
        return self::fromArray($validator->getValidated());
    }

    public static function fromArray(array $data): static
    {
        $static = new static();

        foreach ((new ReflectionClass($static))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!isset($data[$property->getName()]) && !$property->hasDefaultValue())
                throw new OutOfBoundsException(sprintf('Argument $data does\'t have \'%s\' key', $property->getName()));

            $property->setValue($static, $data[$property->getName()] ?? $property->getDefaultValue());
        }

        return $static;
    }
}