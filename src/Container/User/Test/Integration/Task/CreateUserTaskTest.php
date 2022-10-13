<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $userPasswordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $createUserTask = new CreateUserTask($userPasswordHasher, $entityManager);

        $user = $createUserTask->run('user', 'user@mail.com', 'password');

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('user', $user->getLogin());
        $this->assertSame('user@mail.com', $user->getEmail());
    }
}
