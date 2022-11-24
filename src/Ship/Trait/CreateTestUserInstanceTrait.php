<?php

declare(strict_types=1);

namespace App\Ship\Trait;

use App\Container\User\Entity\Doc\User;

trait CreateTestUserInstanceTrait
{
    protected static function createUser(): User
    {
        return new User('user', 'user@mail.com', 'password', static fn (): string => 'hash');
    }
}
