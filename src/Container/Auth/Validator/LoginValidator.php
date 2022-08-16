<?php

namespace App\Container\Auth\Validator;

use App\Ship\Parent\Validator\PropertyValidator;
use Symfony\Component\Validator\Constraints as Assert;

class LoginValidator extends PropertyValidator
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    private string $login;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    private string $password;
}