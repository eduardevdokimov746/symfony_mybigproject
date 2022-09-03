<?php

namespace App\Container\AuthSection\Auth\Event;

use App\Container\User\Entity\Doc\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    public const NAME = 'user.registered';

    public function __construct(
        private User $user
    )
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}