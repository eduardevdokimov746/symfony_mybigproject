<?php

namespace App\Container\Locale\Action;

use App\Container\Locale\Task\SwitchTask;
use App\Ship\Parent\Action;
use App\Ship\Task\GetRefererRouteByUrlTask;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SwitchAction extends Action
{
    public const FALLBACK_ROUTE = 'home.main';

    public function __construct(
        private GetRefererRouteByUrlTask $routeByUrlTask,
        private SwitchTask               $switchTask,
        private string                   $cookieName,
        private string                   $cookieExpire,
        private UrlGeneratorInterface    $urlGenerator
    )
    {
    }

    public function run(?string $refererUrl)
    {
        $newLocale = $this->switchTask->run();

        if (!is_null($refererUrl))
            $refererRoute = $this->routeByUrlTask->getOnly($refererUrl, $this->routeByUrlTask::ROURE);

        $redirectUrl = $this->urlGenerator->generate(
            $refererRoute ?? self::FALLBACK_ROUTE,
            $this->mapQueryString($refererUrl),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->makeResponseWithLocaleCookie($redirectUrl, $newLocale);
    }

    private function mapQueryString(string $refererUrl): array
    {
        $queryString = [];

        parse_str(parse_url($refererUrl)['query'] ?? '', $queryString);

        return $queryString;
    }

    private function makeResponseWithLocaleCookie(string $redirectUrl, string $newLocale): Response
    {
        $localeCookie = new Cookie($this->cookieName, $newLocale, $this->cookieExpire);

        $response = new RedirectResponse($redirectUrl);

        $response->headers->setCookie($localeCookie);

        return $response;
    }
}