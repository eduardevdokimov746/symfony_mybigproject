<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContext;

class RequestContextListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestContext $requestContext,
        private string $host,
        private int $httpPort,
        private int $httpsPort,
        private string $scheme
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
            $this->requestContext->setHost($this->host);
            $this->requestContext->setHttpPort($this->httpPort);
            $this->requestContext->setHttpsPort($this->httpsPort);
            $this->requestContext->setScheme($this->scheme);
        }
    }
}
