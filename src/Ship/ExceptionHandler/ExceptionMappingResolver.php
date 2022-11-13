<?php

declare(strict_types=1);

namespace App\Ship\ExceptionHandler;

use InvalidArgumentException;
use Throwable;

class ExceptionMappingResolver
{
    /**
     * @var ExceptionMapping[]
     */
    private array $mappings = [];

    /**
     * @param array<string, array{code?: int, hidden?: bool, loggable?: bool}> $mappings
     *
     * @throws InvalidArgumentException When the mapping does not contain key code
     */
    public function __construct(array $mappings)
    {
        foreach ($mappings as $class => $mapping) {
            if (!isset($mapping['code'])) {
                throw new InvalidArgumentException("Code is mandatory for class {$class}");
            }

            $this->addMapping(
                $class,
                $mapping['code'],
                $mapping['hidden'] ?? ExceptionMapping::HIDDEN,
                $mapping['loggable'] ?? ExceptionMapping::LOGGABLE
            );
        }
    }

    public function resolve(Throwable $throwable): ExceptionMapping
    {
        foreach ($this->mappings as $class => $exceptionMapping) {
            if (is_a($throwable, $class)) {
                $exceptionMapping->setThrowable($throwable);

                return $exceptionMapping;
            }
        }

        return ExceptionMapping::fromCode(500)->setThrowable($throwable);
    }

    private function addMapping(string $class, int $code, bool $hidden, bool $loggable): void
    {
        $this->mappings[$class] = new ExceptionMapping($code, $hidden, $loggable);
    }
}
