<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Container\User\Task\CreateUserAdminTask;
use App\Container\User\Task\CreateUserTask;
use App\Container\User\Test\Trait\CreateUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateUserAdminTaskTest extends TestCase
{
    use CreateUserTrait;

    public function testRun(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $createUserTask = $this->createStub(CreateUserTask::class);
        $createUserTask->method('run')->willReturn($this->createUser()->setRole(RoleEnum::Admin));

        $createUserAdminTask = new CreateUserAdminTask($createUserTask, $entityManager);

        $user = $createUserAdminTask->run('user', 'user@mail.com', 'password');

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(RoleEnum::Admin, $user->getRole());
    }
}
