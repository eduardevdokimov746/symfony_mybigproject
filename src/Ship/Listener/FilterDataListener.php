<?php

namespace App\Ship\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FilterDataListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest()->request;

        foreach ($request->all() as $name => $value) {
            $request->set($name, $this->filter($value));
        }
    }

    private function filter(int|string $value): int|string|null
    {
        return trim($value) ?: null;
    }
}