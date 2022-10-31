<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContext;

class SetFormatListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestContext $requestContext
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $request = $event->getRequest();

            $this->requestContext->setParameter('format', $request->attributes->get('_format'));
        }
    }
}
