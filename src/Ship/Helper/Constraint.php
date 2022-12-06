<?php

declare(strict_types=1);

namespace App\Ship\Helper;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Symfony\Component\Validator\Constraint as AbstractConstraint;

class Constraint
{
    /**
     * @param class-string|object $class
     *
     * @return list<AbstractConstraint>
     */
    public static function fromProperty(string|object $class, string $property): array
    {
        try {
            return self::mapAttributes((new ReflectionClass($class))->getProperty($property));
        } catch (ReflectionException) {
            return [];
        }
    }

    /**
     * @param class-string|object $class
     *
     * @return list<AbstractConstraint>
     */
    public static function fromClass(string|object $class): array
    {
        try {
            return self::mapAttributes(new ReflectionClass($class));
        } catch (ReflectionException) {
            return [];
        }
    }

    /**
     * @param class-string|object $class
     *
     * @return list<AbstractConstraint>
     */
    public static function fromMethod(string|object $class, string $method): array
    {
        try {
            return self::mapAttributes((new ReflectionClass($class))->getMethod($method));
        } catch (ReflectionException) {
            return [];
        }
    }

    /**
     * @phpstan-ignore-next-line
     */
    private static function mapAttributes(ReflectionClass|ReflectionMethod|ReflectionProperty $reflection): array
    {
        $reflectionAttributes = $reflection->getAttributes(AbstractConstraint::class, ReflectionAttribute::IS_INSTANCEOF);

        return array_map(static fn (ReflectionAttribute $attribute): object => $attribute->newInstance(), $reflectionAttributes);
    }
}
