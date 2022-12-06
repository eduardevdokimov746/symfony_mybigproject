<?php

declare(strict_types=1);

namespace App\Container\User\Enum;

enum RoleEnum: string
{
    case Admin = 'ROLE_ADMIN';
    case User = 'ROLE_USER';

    public function translationKey(): string
    {
        return match ($this) {
            RoleEnum::User => 'role_choice.user',
            RoleEnum::Admin => 'role_choice.admin',
        };
    }
}
