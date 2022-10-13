<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Task\FindUserByIdTask;
use App\Container\User\Task\MarkUserEmailVerifiedTask;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MarkUserEmailVerifiedTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $findUserById = self::getContainer()->get(FindUserByIdTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $user = $findUserById->run(1);

        $markUserEmailVerifiedTask = new MarkUserEmailVerifiedTask($findUserById, $entityManager);

        $markUserEmailVerifiedTask->run(1);

        $this->assertTrue($user->isEmailVerified());
    }
}
