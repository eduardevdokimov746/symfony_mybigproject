<?php

declare(strict_types=1);

namespace App\Container\User\Test\Trait;

use App\Container\User\Entity\Doc\User;

trait CreateUserTrait
{
    private function createUser(): User
    {
        return new User('user', 'user@mail.com', 'password', fn () => 'hash');
    }
}
