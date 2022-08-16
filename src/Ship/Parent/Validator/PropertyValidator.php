<?php

namespace App\Ship\Parent\Validator;

use App\Ship\Parent\Validator;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class PropertyValidator extends Validator
{
    public function __construct(
        private ValidatorInterface $validator
    )
    {
    }

    public function validate(array $data): array
    {
        $this->start = true;
        $this->data = $data;

        foreach ($this->getProperties() as $propertyName) {
            $errorList = $this->validator->validatePropertyValue($this, $propertyName, $data[$propertyName] ?? null);
            if ($errorList->count() > 0) {
                $this->errors[$propertyName] = $errorList->get(0)->getMessage();
                $this->valid = false;
            } else {
                unset($this->errors[$propertyName]);
            }
        }

        if (empty($this->errors)) $this->valid = true;

        return $this->errors;
    }

    protected function getProperties(): array
    {
        $properties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PRIVATE);

        return array_map(fn(ReflectionProperty $property) => $property->name, $properties);
    }
}