<?php

declare(strict_types=1);

namespace App\Container\User\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Trait\UserPasswordHasherTrait;
use App\Ship\Parent\Task;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTask extends Task
{
    use UserPasswordHasherTrait;

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function run(string $login, string $email, string $plainPassword, callable $callback = null): User
    {
        $user = new User(
            $login,
            $email,
            $plainPassword,
            $this->userPasswordHasher($this->passwordHasher)
        );

        $this->call($callback, $user);

        $this->persistAndFlush($user);

        return $user;
    }
}
