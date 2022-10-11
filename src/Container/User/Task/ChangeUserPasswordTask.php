<?php

declare(strict_types=1);

namespace App\Container\User\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Trait\UserPasswordHasherTrait;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordTask extends Task
{
    use UserPasswordHasherTrait;

    public function __construct(
        private FindUserByIdTask $findUserById,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
    }

    public function run(int $id, string $plainPassword): User
    {
        $user = $this->findUserById->run($id);

        $user->setPassword(
            $this->userPasswordHasher($this->passwordHasher),
            $plainPassword
        );

        $this->entityManager->flush();

        $this->logger->debug('Password changed');

        return $user;
    }
}
