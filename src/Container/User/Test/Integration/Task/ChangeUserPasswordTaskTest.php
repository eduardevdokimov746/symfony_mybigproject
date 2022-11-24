<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $logger = self::getContainer()->get(LoggerInterface::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $user = self::findUserFromDB();

        $oldPassword = $user->getPassword();

        $changeUserPasswordTask = new ChangeUserPasswordTask($passwordHasher, $logger);
        $changeUserPasswordTask->setEntityManager($entityManager);

        $userWithNewPassword = $changeUserPasswordTask->run($user, 'new-password');

        self::assertInstanceOf(User::class, $userWithNewPassword);
        self::assertNotSame($oldPassword, $userWithNewPassword->getPassword());
    }
}
