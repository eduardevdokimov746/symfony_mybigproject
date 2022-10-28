<?php

declare(strict_types=1);

namespace App\Container\Locale\Listener;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class LocaleListener implements EventSubscriberInterface
{
    public function __construct(
        private LocaleSwitcher $localeSwitcher,
        private UrlGeneratorInterface $urlGenerator,
        private string $defaultLocale,
        private string $localeCookieName,
        private bool $useAcceptLanguageHeader = false,
        private array $enabledLocales = []
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 17],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (null === ($locale = $request->cookies->get($this->localeCookieName))) {
            $locale = $this->getFromAcceptLanguageHeader($request) ?: $this->defaultLocale;
        }

        $this->localeSwitcher->setLocale($locale);

        try {
            if (
                $request->attributes->get('_locale', $locale) !== $locale
                && $request->attributes->get('_route')
            ) {
                $event->setResponse(new RedirectResponse($this->makeRedirectUrl($request)));
            }
        } catch (Exception) {
        }
    }

    private function getFromAcceptLanguageHeader(Request $request): string|false
    {
        if (!$this->useAcceptLanguageHeader || empty($this->enabledLocales)) {
            return false;
        }

        return $request->getPreferredLanguage($this->enabledLocales);
    }

    private function makeRedirectUrl(Request $request): string
    {
        return $this->urlGenerator->generate($request->attributes->get('_route'));
    }
}
