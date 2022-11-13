<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Test\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTaskTest extends TestCase
{
    public function testRun(): void
    {
        $passwordHasher = self::createStub(UserPasswordHasherInterface::class);
        $entityManager = self::createStub(EntityManagerInterface::class);

        $passwordHasher->method('hashPassword')->willReturn('hash');

        $createUserTask = new CreateUserTask($passwordHasher, $entityManager);

        $user = $createUserTask->run('user', 'user@mail.com', 'password');

        self::assertInstanceOf(User::class, $user);
        self::assertSame('user', $user->getLogin());
        self::assertSame('user@mail.com', $user->getEmail());
        self::assertSame('hash', $user->getPassword());
    }
}
