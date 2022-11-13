<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Container\User\Task\CreateUserAdminTask;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserAdminTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $createUserTask = self::getContainer()->get(CreateUserTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $createUserAdminTask = new CreateUserAdminTask($createUserTask, $entityManager);

        $user = $createUserAdminTask->run('user', 'user@mail.com', 'password');

        self::assertInstanceOf(User::class, $user);
        self::assertSame(RoleEnum::Admin, $user->getRole());
    }
}
