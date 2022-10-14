<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Test\KernelTestCase;

class FindUserByIdTaskTest extends KernelTestCase
{
    private FindUserByIdTask $findUserByIdTask;

    protected function setUp(): void
    {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $this->findUserByIdTask = new FindUserByIdTask($userRepository);
    }

    public function testRunOnlyActive(): void
    {
        $user = $this->findUserByIdTask->run(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->isActive());
    }

    public function testRunWithDisabled(): void
    {
        $user = $this->findUserByIdTask->run(3, true);

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isActive());
    }

    public function testRunExpectException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->findUserByIdTask->run(1000);
    }
}
