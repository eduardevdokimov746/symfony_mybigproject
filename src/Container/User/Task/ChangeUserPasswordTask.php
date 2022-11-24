<?php

declare(strict_types=1);

namespace App\Container\User\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Trait\UserPasswordHasherTrait;
use App\Ship\Parent\Task;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordTask extends Task
{
    use UserPasswordHasherTrait;

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private LoggerInterface $logger
    ) {
    }

    public function run(User $user, string $plainPassword): User
    {
        $user->setPassword($this->userPasswordHasher($this->passwordHasher), $plainPassword);

        $this->flush();

        $this->logger->debug('Password changed');

        return $user;
    }
}
