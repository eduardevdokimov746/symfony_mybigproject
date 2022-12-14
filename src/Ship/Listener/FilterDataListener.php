<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FilterDataListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $request = $event->getRequest()->request;

            foreach ($request->all() as $name => $value) {
                if (is_string($value)) {
                    $request->set($name, $this->filter($value));
                }
            }
        }
    }

    private function filter(string $value): ?string
    {
        $value = trim(htmlspecialchars($value));

        return '' === $value ? null : $value;
    }
}
