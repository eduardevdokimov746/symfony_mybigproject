<?php

declare(strict_types=1);

namespace App\Container\User\Task;

use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;

class MarkUserEmailVerifiedTask extends Task
{
    public function __construct(
        private FindUserById $findUserById,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function run(int $userId): void
    {
        $user = $this->findUserById->run($userId);

        $user->setEmailVerified(true);

        $this->entityManager->flush();
    }
}