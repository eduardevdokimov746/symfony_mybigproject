<?php

declare(strict_types=1);

namespace App\Container\Profile\Validator;

use App\Ship\Parent\Validator\PropertyValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class EditPasswordValidator extends PropertyValidator
{
    #[Assert\NotBlank]
    #[SecurityAssert\UserPassword]
    private string $oldPlainPassword;

    #[Assert\NotBlank]
    #[Assert\NotEqualTo(propertyPath: 'oldPlainPassword', message: 'new_password_is_same')]
    #[Assert\Length(min: 3)]
    private string $newPlainPassword;

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'newPlainPassword', message: 'password_does_match')]
    private string $newPlainPasswordConfirmation;
}
