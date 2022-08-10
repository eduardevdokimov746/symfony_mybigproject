<?php

namespace App\Container\Locale\Listener;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

class LocaleListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestContext        $requestContext,
        private UrlGeneratorInterface $urlGenerator,
        private string                $defaultLocale,
        private string                $localeCookieName,
        private bool                  $useAcceptLanguageHeader = false,
        private array                 $enabledLocales = []
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 17]
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $localeFromAcceptLanguageHeader = $this->getFromAcceptLanguageHeader($request) ?: $this->defaultLocale;

        if ($localeFromCookie = $request->cookies->get($this->localeCookieName)) {
            $request->setLocale($localeFromCookie);
            return;
        }

        try {
            if (
                $request->attributes->get('_locale') !== $localeFromAcceptLanguageHeader &&
                $request->attributes->get('_route')
            )
                $event->setResponse(new RedirectResponse($this->makeRedirectUrl($request, $localeFromAcceptLanguageHeader)));

        } catch (Exception) {}
    }

    private function getFromAcceptLanguageHeader(Request $request): string|false
    {
        if ($this->useAcceptLanguageHeader && $this->enabledLocales)
            return $request->getPreferredLanguage($this->enabledLocales);

        return false;
    }

    private function makeRedirectUrl(Request $request, string $localeFromAcceptLanguageHeader): string
    {
        $this->requestContext->setParameter('_locale', $localeFromAcceptLanguageHeader);

        $this->urlGenerator->setContext($this->requestContext);

        return $this->urlGenerator->generate($request->attributes->get('_route'));
    }
}