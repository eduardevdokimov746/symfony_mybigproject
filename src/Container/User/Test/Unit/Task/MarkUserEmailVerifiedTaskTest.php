<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Task\FindUserByIdTask;
use App\Container\User\Task\MarkUserEmailVerifiedTask;
use App\Ship\Parent\Test\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class MarkUserEmailVerifiedTaskTest extends TestCase
{
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
