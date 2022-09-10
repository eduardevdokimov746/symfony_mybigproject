<?php

declare(strict_types=1);

namespace App\Container\Profile\Exception;

use RuntimeException;

class ProfileNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Profile not found');
    }
}
