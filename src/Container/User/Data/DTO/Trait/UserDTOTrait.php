<?php

declare(strict_types=1);

namespace App\Container\User\Data\DTO\Trait;

use App\Container\User\Entity\Doc\User;
use App\Ship\Validator\Constraints\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;

trait UserDTOTrait
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[UniqueConstraint(entity: User::class, message: 'login_already_exists')]
    public readonly string $login;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[UniqueConstraint(entity: User::class, message: 'email_already_exists')]
    public readonly string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    public readonly string $plainPassword;
}
