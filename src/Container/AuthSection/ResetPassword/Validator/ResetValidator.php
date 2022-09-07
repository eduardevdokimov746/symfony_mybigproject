<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Validator;

use App\Ship\Parent\Validator\PropertyValidator;
use Symfony\Component\Validator\Constraints as Assert;

class ResetValidator extends PropertyValidator
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    private string $plainPassword;

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'plainPassword', message: 'password_does_match')]
    private string $plainPasswordConfirmation;
}