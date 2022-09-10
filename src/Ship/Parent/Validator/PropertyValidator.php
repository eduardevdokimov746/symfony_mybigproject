<?php

declare(strict_types=1);

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
    ) {
    }

    public function __get(string $name)
    {
        foreach ($this->mapValidationProperties() as $property) {
            if ($property->getName() === $name) {
                return $property->getValue($this);
            }
        }

        throw new PropertyClassNotExists($this::class, $name);
    }

    public function validate(array $data): array
    {
        $this->reset();

        $this->setData($data);

        /** @var ConstraintViolation $error */
        foreach ($this->validator->validate($this) as $error) {
            $this->addError($error->getPropertyPath(), $error->getMessage());
        }

        if (empty($this->errors)) {
            $this->valid = true;
        }

        return $this->errors;
    }

    /**
     * @return ReflectionProperty[]
     */
    private function mapValidationProperties(): array
    {
        $allProperties = (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PRIVATE);

        return array_filter($allProperties, fn (ReflectionProperty $property) => !empty($property->getAttributes()));
    }

    private function setData(array $data): void
    {
        foreach ($this->mapValidationProperties() as $property) {
            $propertyName = $property->getName();

            if (isset($data[$propertyName])) {
                $this->data[$propertyName] = $data[$propertyName];
                $property->setValue($this, $this->data[$propertyName]);
            }
        }
    }

    private function addError(string $property, string $error): void
    {
        if (!isset($this->errors[$property])) {
            $this->errors[$property] = $error;
        }
    }
}
