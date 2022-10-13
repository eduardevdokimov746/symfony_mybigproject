<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Container\User\Task\FindUserByIdTask;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $findUserByIdTask = self::getContainer()->get(FindUserByIdTask::class);
        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $logger = self::getContainer()->get(LoggerInterface::class);

        $user = $findUserByIdTask->run(1);

        $oldPassword = $user->getPassword();

        $changeUserPasswordTask = new ChangeUserPasswordTask($findUserByIdTask, $passwordHasher, $entityManager, $logger);

        $userWithNewPassword = $changeUserPasswordTask->run(1, 'new-password');

        $this->assertInstanceOf(User::class, $userWithNewPassword);
        $this->assertNotSame($oldPassword, $userWithNewPassword->getPassword());
    }
}
