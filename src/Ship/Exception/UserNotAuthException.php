<?php

declare(strict_types=1);

namespace App\Ship\Exception;

use RuntimeException;

class UserNotAuthException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User not authenticated');
    }
}
