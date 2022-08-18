<?php

namespace App\Container\Auth\Listener;

use App\Container\Auth\Event\UserRegisteredEvent;

class SendVerificationEmailListener
{
    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        // отправка почты
    }
}