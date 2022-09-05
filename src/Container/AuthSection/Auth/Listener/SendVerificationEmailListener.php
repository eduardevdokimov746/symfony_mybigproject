<?php

namespace App\Container\AuthSection\Auth\Listener;

use App\Container\AuthSection\Auth\Action\SendEmailVerificationAction;
use App\Container\AuthSection\Auth\Event\UserRegisteredEvent;

class SendVerificationEmailListener
{
    public function __construct(
        private SendEmailVerificationAction $sendEmailVerificationAction
    )
    {
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $this->sendEmailVerificationAction->run($event->getUser());
    }
}