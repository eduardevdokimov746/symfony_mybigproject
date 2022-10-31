<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Data\DTO;

use App\Ship\Parent\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class GenerateAndSendResetPasswordTokenDTO extends DTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public readonly ?string $email;
}
