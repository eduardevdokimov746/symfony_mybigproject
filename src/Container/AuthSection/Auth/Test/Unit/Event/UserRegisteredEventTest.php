<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Unit\Event;

use App\Container\AuthSection\Auth\Event\UserRegisteredEvent;
use App\Container\User\Entity\Doc\User;
use Monolog\Test\TestCase;

class UserRegisteredEventTest extends TestCase
{
    public function testGetUser(): void
    {
        $user = new User('user', 'user@mail.com', 'password', fn () => 'hash');

        $userRegisteredEvent = new UserRegisteredEvent($user);

        $this->assertEquals($user, $userRegisteredEvent->getUser());
    }
}
