<?php

namespace App\Container\Auth\Exception;

use RuntimeException;

class SaveByRegistrationException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Failed to save to the user\'s database during registration');
    }
}