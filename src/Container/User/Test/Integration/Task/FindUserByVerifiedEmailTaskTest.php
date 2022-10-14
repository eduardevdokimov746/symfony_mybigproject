<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Task;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Container\User\Task\FindUserByVerifiedEmailTask;
use App\Ship\Parent\Test\KernelTestCase;

class FindUserByVerifiedEmailTaskTest extends KernelTestCase
{
    private FindUserByVerifiedEmailTask $findUserByVerifiedEmailTask;

    protected function setUp(): void
    {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $this->findUserByVerifiedEmailTask = new FindUserByVerifiedEmailTask($userRepository);
    }

    public function testRun(): void
    {
        $user = $this->findUserByVerifiedEmailTask->run('admin@mail.com');

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->isEmailVerified());
    }

    public function testRunExpectException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->findUserByVerifiedEmailTask->run('eee@mail.com');
    }
}
