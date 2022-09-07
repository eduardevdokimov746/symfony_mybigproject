<?php

namespace App\Container\AuthSection\ResetPassword\Validator;

use App\Ship\Parent\Validator\PropertyValidator;
use Symfony\Component\Validator\Constraints as Assert;

class RequestValidator extends PropertyValidator
{
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;
}