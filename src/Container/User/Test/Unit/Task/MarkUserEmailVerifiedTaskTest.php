<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Task\FindUserByIdTask;
use App\Container\User\Task\MarkUserEmailVerifiedTask;
use App\Container\User\Test\Trait\CreateUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class MarkUserEmailVerifiedTaskTest extends TestCase
{
    use CreateUserTrait;

    public function testRun(): void
    {
        $user = $this->createUser();

        $findUserById = $this->createStub(FindUserByIdTask::class);
        $entityManager = $this->createStub(EntityManagerInterface::class);

        $findUserById->method('run')->willReturn($user);

        $markUserEmailVerifiedTask = new MarkUserEmailVerifiedTask($findUserById, $entityManager);

        $markUserEmailVerifiedTask->run(1);

        $this->assertTrue($user->isEmailVerified());
    }
}
