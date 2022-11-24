<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTaskTest extends KernelTestCase
{
    private CreateUserTask $createUserTask;

    protected function setUp(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $userPasswordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $this->createUserTask = new CreateUserTask($userPasswordHasher);
        $this->createUserTask->setEntityManager($entityManager);
    }

    public function testRun(): void
    {
        $user = $this->createUserTask->run('user', 'user@mail.com', 'password');

        self::assertInstanceOf(User::class, $user);
        self::assertSame('user', $user->getLogin());
        self::assertSame('user@mail.com', $user->getEmail());
    }

    public function testRunWithCallback(): void
    {
        $callback = static function (User $user): void {
            $user
                ->disable()
                ->setEmailVerified(true)
            ;
        };

        $user = $this->createUserTask->run('user', 'user@mail.com', 'password', $callback);

        self::assertInstanceOf(User::class, $user);
        self::assertSame('user', $user->getLogin());
        self::assertSame('user@mail.com', $user->getEmail());
        self::assertFalse($user->isActive());
        self::assertTrue($user->isEmailVerified());
    }
}
