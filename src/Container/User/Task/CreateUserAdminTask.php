<?php

declare(strict_types=1);

namespace App\Container\User\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserAdminTask extends Task
{
    public function __construct(
        private CreateUserTask $createUserTask,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function run(string $login, string $email, string $plainPassword): User
    {
        $user = $this->createUserTask->run($login, $email, $plainPassword);

        $user->setRole(RoleEnum::Admin);

        $this->entityManager->flush();

        return $user;
    }
}
