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
        $passwordHasher = self::createStub(UserPasswordHasherInterface::class);
        $entityManager = self::createStub(EntityManagerInterface::class);
        $findUserById = self::createStub(FindUserByIdTask::class);
        $logger = self::createStub(LoggerInterface::class);

        $passwordHasher->method('hashPassword')->willReturn('new-hash');

        $user = $this->createUser();

        $findUserById->method('run')->willReturn($user);

        $changeUserPasswordTask = new ChangeUserPasswordTask($findUserById, $passwordHasher, $entityManager, $logger);

        $userWithNewPassword = $changeUserPasswordTask->run(1, 'new-password');

        self::assertInstanceOf(User::class, $userWithNewPassword);
        self::assertSame('new-hash', $userWithNewPassword->getPassword());
    }
}
