<?php

namespace App\Ship\Listener;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTokenListener implements EventSubscriberInterface
{
    public function __construct(
        private CsrfTokenManagerInterface $csrfTokenManager,
        #[Autowire('%form.type_extension.csrf.field_name%')]
        private string                    $csrfFieldName
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (
            in_array($request->getMethod(), ValidationListener::CHECK_METHODS) &&
            !$this->csrfTokenManager->isTokenValid(new CsrfToken($this->csrfFieldName, $request->request->get($this->csrfFieldName)))
        )
            throw new InvalidCsrfTokenException('Csrf token is not valid');
    }
}