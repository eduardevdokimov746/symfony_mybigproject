<?php

declare(strict_types=1);

namespace App\Ship\Exception;

use RuntimeException;

class FileException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
