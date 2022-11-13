<?php

declare(strict_types=1);

namespace App\Ship\Parent;

use App\Ship\Attribute\DefaultValue;
use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

abstract class DTO
{
    /**
     * @param array<string, mixed> $data
     */
    final private function __construct(array $data)
    {
        $defaultValue = null;
        $properties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_READONLY);

        foreach ($properties as $property) {
            if (null === $propertyType = $property->getType()) {
                throw new LogicException('Property {'.$property->name.'} must be explicitly typed');
            }

            $defaultValueAttributes = $property->getAttributes(DefaultValue::class);
            $hasDefaultValue = 0 < count($defaultValueAttributes);

            if ($hasDefaultValue) {
                $defaultValueAttribute = $defaultValueAttributes[0]->newInstance();

                $defaultValue = $defaultValueAttribute->getValue();
            }

            $dataHasProperty = array_key_exists($property->name, $data);

            if (!$dataHasProperty && !$hasDefaultValue && !$propertyType->allowsNull()) {
                throw new InvalidArgumentException('Value for property {'.$property->name.'} does not exists');
            }

            if ($dataHasProperty && is_null($data[$property->name]) && !$propertyType->allowsNull()) {
                continue;
            }

            $property->setValue($this, $dataHasProperty ? $data[$property->name] : $defaultValue);
        }
    }

    /**
     * @param array<string, mixed>|Request $data
     */
    final public static function create(array|Request $data): static
    {
        if ($data instanceof Request) {
            $data = array_merge($data->request->all(), $data->query->all(), $data->files->all());
        }

        return new static($data);
    }
}
