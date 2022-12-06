<?php

declare(strict_types=1);

namespace App\Ship\Test\Integration\Helper;

use App\Ship\Helper\Constraint;
use App\Ship\Parent\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints as Assert;

class ConstraintTest extends KernelTestCase
{
    /**
     * @param class-string|object $class
     *
     * @dataProvider classDataProvider
     */
    public function testFromProperty(string|object $class): void
    {
        $constraints = Constraint::fromProperty($class, 'prop');

        self::assertEquals([new Assert\Type('int'), new Assert\GreaterThan(value: 10)], $constraints);
    }

    /**
     * @param class-string|object $class
     *
     * @dataProvider badDataProvider
     */
    public function testFromPropertyBadArguments(string|object $class, string $property): void
    {
        $constraints = Constraint::fromProperty($class, $property);

        self::assertEmpty($constraints);
    }

    /**
     * @param class-string|object $class
     *
     * @dataProvider classDataProvider
     */
    public function testFromClass(string|object $class): void
    {
        $constraints = Constraint::fromClass($class);

        self::assertEquals([new Assert\Cascade(), new Assert\Callback()], $constraints);
    }

    /**
     * @param class-string|object $class
     *
     * @dataProvider classDataProvider
     */
    public function testFromMethod(string|object $class): void
    {
        $constraints = Constraint::fromMethod($class, 'met');

        self::assertEquals([new Assert\NotBlank(), new Assert\IsTrue()], $constraints);
    }

    /**
     * @return list<list<class-string|object>>
     */
    public function classDataProvider(): array
    {
        return [
            [new ConstraintTestClass()],
            [ConstraintTestClass::class],
        ];
    }

    /**
     * @return list<list<object|string>>
     */
    public function badDataProvider(): array
    {
        return [
            ['class not exists', 'prop'],
            [new ConstraintTestClass(), 'property not exists'],
        ];
    }
}
