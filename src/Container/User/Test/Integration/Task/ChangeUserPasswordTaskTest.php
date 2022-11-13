<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $findUserByIdTask = self::getContainer()->get(FindUserByIdTask::class);
        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $logger = self::getContainer()->get(LoggerInterface::class);

        $user = $this->findUserFromDB();

        $oldPassword = $user->getPassword();

        $changeUserPasswordTask = new ChangeUserPasswordTask($findUserByIdTask, $passwordHasher, $entityManager, $logger);

        $userWithNewPassword = $changeUserPasswordTask->run(1, 'new-password');

        self::assertInstanceOf(User::class, $userWithNewPassword);
        self::assertNotSame($oldPassword, $userWithNewPassword->getPassword());
    }
}
