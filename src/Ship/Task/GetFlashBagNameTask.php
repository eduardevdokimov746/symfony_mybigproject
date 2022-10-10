<?php

declare(strict_types=1);

namespace App\Ship\Task;

use DomainException;

/**
 * @codeCoverageIgnore
 */
class GetFlashBagNameTask
{
    public const ERROR = 'error.';
    public const FIELD = 'field.';

    public function forError(string $key): string
    {
        return $this->run($key, self::ERROR);
    }

    public function forField(string $key): string
    {
        return $this->run($key, self::FIELD);
    }

    public function run(string $key, string $prefix): string
    {
        return match ($prefix) {
            self::ERROR => $prefix.$key,
            self::FIELD => $prefix.$key,
            default => throw new DomainException("'{$prefix}' value is not supported.")
        };
    }
}
