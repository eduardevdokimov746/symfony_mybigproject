<?php

declare(strict_types=1);

namespace App\Container\User\Test\Unit\Task;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Container\User\Task\FindUserByVerifiedEmailTask;
use App\Ship\Parent\Test\TestCase;

class FindUserByVerifiedEmailTaskTest extends TestCase
{
    public function testRun(): void
    {
        $user = self::createUser()->setEmailVerified(true);

        $userRepository = self::createStub(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn($user);

        $findUserByVerifiedEmailTask = new FindUserByVerifiedEmailTask($userRepository);

        $findUser = $findUserByVerifiedEmailTask->run('user@mail.com');

        self::assertInstanceOf(User::class, $findUser);
    }

    public function testRunExpectException(): void
    {
        $userRepository = self::createStub(UserRepository::class);

        $findUserByVerifiedEmailTask = new FindUserByVerifiedEmailTask($userRepository);

        $this->expectException(UserNotFoundException::class);

        $findUserByVerifiedEmailTask->run('user@mail.com');
    }
}
