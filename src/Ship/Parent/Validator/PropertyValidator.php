<?php

namespace App\Ship\Parent\Validator;

use App\Ship\Exception\PropertyClassNotExists;
use App\Ship\Parent\Validator;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class PropertyValidator extends Validator
{
    public function __construct(
        private ValidatorInterface $validator
    )
    {
    }

    public function __get(string $name)
    {
        foreach ($this->mapValidationProperties() as $property)
            if ($property->getName() === $name)
                return $property->getValue($this);

        throw new PropertyClassNotExists($this::class, $name);
    }

    /**
     * @return ReflectionProperty[]
     */
    private function mapValidationProperties(): array
    {
        return (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PRIVATE);
    }

    public function validate(array $data): array
    {
        $this->start = true;
        $this->data = $data;
        $this->errors = [];
        $this->valid = false;

        foreach ($this->mapValidationProperties() as $property)
            if (!empty($property->getAttributes()))
                $property->setValue($this, $data[$property->getName()]);

        /** @var ConstraintViolation $error */
        foreach ($this->validator->validate($this) as $error)
            if (!isset($this->errors[$error->getPropertyPath()]))
                $this->errors[$error->getPropertyPath()] = $error->getMessage();

        if (empty($this->errors)) $this->valid = true;

        return $this->errors;
    }
}