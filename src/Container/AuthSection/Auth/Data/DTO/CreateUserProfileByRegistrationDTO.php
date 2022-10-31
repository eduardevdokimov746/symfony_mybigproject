<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Data\DTO;

use App\Container\User\Entity\Doc\User;
use App\Ship\Attribute\DefaultValue;
use App\Ship\Parent\DTO;
use App\Ship\Validator\Constraints\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserProfileByRegistrationDTO extends DTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[UniqueConstraint(entity: User::class, message: 'login_already_exists')]
    #[DefaultValue(value: 'test')]
    public readonly ?string $login;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[UniqueConstraint(entity: User::class, message: 'email_already_exists')]
    public readonly ?string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    public readonly ?string $plainPassword;
}
