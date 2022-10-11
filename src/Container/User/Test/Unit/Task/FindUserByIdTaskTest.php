<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Container\User\Task\FindUserByIdTask;
use App\Container\User\Test\Trait\CreateUserTrait;
use PHPUnit\Framework\TestCase;

class FindUserByIdTaskTest extends TestCase
{
    use CreateUserTrait;

    private FindUserByIdTask $findUserByIdTask;

    protected function setUp(): void
    {
        $activeUser = $this->createUser()->activate();
        $disabledUser = $this->createUser()->disable();

        $userRepository = $this->createStub(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn($disabledUser);
        $userRepository->method('findActiveOneBy')->willReturn($activeUser);

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
        $user = $this->findUserByIdTask->run(1, true);

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isActive());
    }

    public function testRunExpectException(): void
    {
        $userRepository = $this->createStub(UserRepository::class);

        $findUserByIdTask = new FindUserByIdTask($userRepository);

        $this->expectException(UserNotFoundException::class);

        $findUserByIdTask->run(1);
    }
}
