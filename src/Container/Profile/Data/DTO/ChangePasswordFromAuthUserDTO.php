<?php

declare(strict_types=1);

namespace App\Container\Profile\Data\DTO;

use App\Ship\Parent\DTO;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordFromAuthUserDTO extends DTO
{
    #[Assert\NotBlank]
    #[SecurityAssert\UserPassword]
    public readonly string $oldPlainPassword;

    #[Assert\NotBlank]
    #[Assert\NotEqualTo(propertyPath: 'oldPlainPassword', message: 'new_password_is_same')]
    #[Assert\Length(min: 3)]
    public readonly string $newPlainPassword;

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'newPlainPassword', message: 'password_does_match')]
    public readonly string $newPlainPasswordConfirmation;
}
