<?php

declare(strict_types=1);

namespace App\Ship\Exception;

use RuntimeException;

class PropertyClassNotExists extends RuntimeException
{
    public function __construct(string $class, string $property)
    {
        parent::__construct("Property '{$property}' in class '{$class}' not exists");
    }
}
