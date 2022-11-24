<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Unit\Event;

use App\Container\AuthSection\Auth\Event\UserRegisteredEvent;
use App\Ship\Parent\Test\TestCase;

class UserRegisteredEventTest extends TestCase
{
    public function testGetUser(): void
    {
        $user = self::createUser();

        $userRegisteredEvent = new UserRegisteredEvent($user);

        self::assertEquals($user, $userRegisteredEvent->getUser());
    }
}
