<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $event->getRequest()->getSession()->remove(Security::LAST_USERNAME);
    }
}