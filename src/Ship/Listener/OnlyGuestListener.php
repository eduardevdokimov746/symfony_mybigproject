<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use App\Ship\Attribute\OnlyGuest;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OnlyGuestListener implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private TokenStorageInterface $tokenStorage,
        private string $fallbackRoute
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER => 'onKernelController'];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if ($this->isUserLogged() && $event->isMainRequest()) {
            if (null === ($attr = $this->findOnlyGuestAttribute($event->getController()))) {
                return;
            }

            $event->setController($this->makeRedirectController($attr));
        }
    }

    private function isUserLogged(): bool
    {
        return $this->tokenStorage->getToken()?->getUser() instanceof UserInterface;
    }

    private function findOnlyGuestAttribute(callable $controller): ?OnlyGuest
    {
        $reflection = is_array($controller) ? new ReflectionMethod(...$controller) : new ReflectionClass($controller);

        if (empty($attrs = $reflection->getAttributes(OnlyGuest::class))) {
            return null;
        }

        if (count($attrs) > 1) {
            throw new RuntimeException(sprintf('More than one %s attribute passed', OnlyGuest::class));
        }

        return $attrs[0]->newInstance();
    }

    private function makeRedirectController(OnlyGuest $onlyGuest): callable
    {
        $redirectUrl = $this->urlGenerator->generate($onlyGuest->getRedirectRoute() ?? $this->fallbackRoute);

        return static function () use ($redirectUrl): Response {
            return new RedirectResponse($redirectUrl);
        };
    }
}
