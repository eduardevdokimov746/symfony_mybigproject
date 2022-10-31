<?php

declare(strict_types=1);

namespace App\Ship\Parent;

use App\Ship\Attribute\DefaultValue;
use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

abstract class DTO
{
    private function __construct(array $data)
    {
        $properties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            /** @var ReflectionAttribute[] */
            $defaultValueAttributes = $property->getAttributes(DefaultValue::class);

            if (!empty($defaultValueAttributes)) {
                /** @var DefaultValue $defaultValueAttribute */
                $defaultValueAttribute = $defaultValueAttributes[0]->newInstance();

                $defaultValue = $defaultValueAttribute->getValue();
            }

            $dataHasProperty = array_key_exists($property->name, $data);

            if (!$dataHasProperty && empty($defaultValueAttributes)) {
                throw new InvalidArgumentException('Value for property {'.$property->name.'} does not exists');
            }

            $property->setValue($this, $dataHasProperty ? $data[$property->name] : $defaultValue);
        }
    }

    final public static function create(array|Request $data): static
    {
        if ($data instanceof Request) {
            $data = array_merge($data->request->all(), $data->query->all(), $data->files->all());
        }

        return new static($data);
    }
}
