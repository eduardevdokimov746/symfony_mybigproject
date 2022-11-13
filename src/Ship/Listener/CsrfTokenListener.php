<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTokenListener implements EventSubscriberInterface
{
    private const CHECK_METHODS = [
        'POST',
        'PATCH',
        'PUT',
        'DELETE',
    ];

    public function __construct(
        private CsrfTokenManagerInterface $csrfTokenManager,
        #[Autowire('%csrf_parameter%')]
        private string $csrfParameter,
        #[Autowire('%csrf_token_id%')]
        private string $csrfId,
        #[Autowire('%kernel.environment%')]
        private string $env
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $request = $event->getRequest();

            $requestParametersIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($request->request->all()));

            foreach ($requestParametersIterator as $name => $value) {
                if ($name === $this->csrfParameter) {
                    $checkValue = $value;
                    break;
                }
            }

            if (
                'test' !== $this->env
                && in_array($request->getMethod(), self::CHECK_METHODS, true)
                && !$this->csrfTokenManager->isTokenValid(new CsrfToken($this->csrfId, $checkValue ?? '')) /** @phpstan-ignore-line */
            ) {
                throw new InvalidCsrfTokenException('Csrf token is not valid');
            }
        }
    }
}
