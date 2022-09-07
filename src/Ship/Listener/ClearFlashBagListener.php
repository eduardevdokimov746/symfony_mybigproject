<?php

namespace App\Ship\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ClearFlashBagListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => 'onKernelResponse'];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($event->getResponse()->getStatusCode() === 200)
            $this->requestStack->getSession()->getFlashBag()->clear();
    }
}