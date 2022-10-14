<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Test\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordTaskTest extends TestCase
{
    public function testRun(): void
    {
        $passwordHasher = $this->createStub(UserPasswordHasherInterface::class);
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $findUserById = $this->createStub(FindUserByIdTask::class);
        $logger = $this->createStub(LoggerInterface::class);

        $passwordHasher->method('hashPassword')->willReturn('new-hash');

        $user = $this->createUser();

        $findUserById->method('run')->willReturn($user);

        $changeUserPasswordTask = new ChangeUserPasswordTask($findUserById, $passwordHasher, $entityManager, $logger);

        $userWithNewPassword = $changeUserPasswordTask->run(1, 'new-password');

        $this->assertInstanceOf(User::class, $userWithNewPassword);
        $this->assertSame('new-hash', $userWithNewPassword->getPassword());
    }
}
