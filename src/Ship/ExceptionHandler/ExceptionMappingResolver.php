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
     * @param ExceptionMapping[] $mappings
     */
    public function __construct(array $mappings)
    {
        foreach ($mappings as $class => $mapping) {
            if (empty($mapping['code'])) {
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
            if ($throwable::class === $class || is_subclass_of($throwable::class, $class)) {
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
