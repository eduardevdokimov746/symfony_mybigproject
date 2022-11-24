<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Test\TestCase;

class FindUserByIdTaskTest extends TestCase
{
    private FindUserByIdTask $findUserByIdTask;

    protected function setUp(): void
    {
        $activeUser = self::createUser()->activate();
        $disabledUser = self::createUser()->disable();
        $userRepository = self::createStub(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn($disabledUser);
        $userRepository->method('findActiveOneBy')->willReturn($activeUser);

        $this->findUserByIdTask = new FindUserByIdTask($userRepository);
    }

    public function testRunOnlyActive(): void
    {
        $user = $this->findUserByIdTask->run(1);

        self::assertInstanceOf(User::class, $user);
        self::assertTrue($user->isActive());
    }

    public function testRunWithDisabled(): void
    {
        $user = $this->findUserByIdTask->run(1, true);

        self::assertInstanceOf(User::class, $user);
        self::assertFalse($user->isActive());
    }

    public function testRunExpectException(): void
    {
        $userRepository = self::createStub(UserRepository::class);

        $findUserByIdTask = new FindUserByIdTask($userRepository);

        $this->expectException(UserNotFoundException::class);

        $findUserByIdTask->run(1);
    }
}
