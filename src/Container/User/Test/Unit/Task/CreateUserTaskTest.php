<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTaskTest extends TestCase
{
    public function testRun(): void
    {
        $passwordHasher = $this->createStub(UserPasswordHasherInterface::class);
        $entityManager = $this->createStub(EntityManagerInterface::class);

        $passwordHasher->method('hashPassword')->willReturn('hash');

        $createUserTask = new CreateUserTask($passwordHasher, $entityManager);

        $user = $createUserTask->run('user', 'user@mail.com', 'password');

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('user', $user->getLogin());
        $this->assertSame('user@mail.com', $user->getEmail());
        $this->assertSame('hash', $user->getPassword());
    }
}
