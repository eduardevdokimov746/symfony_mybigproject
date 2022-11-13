<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Data\DTO;

use App\Ship\Parent\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class RemoveResetTokenAndChangeUserPasswordDTO extends DTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    public readonly string $plainPassword;

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'plainPassword', message: 'password_does_match')]
    public readonly string $plainPasswordConfirmation;
}
