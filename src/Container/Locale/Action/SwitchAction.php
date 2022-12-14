<?php

declare(strict_types=1);

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
    public function __construct(
        private GetRefererRouteByUrlTask $routeByUrlTask,
        private SwitchTask $switchTask,
        private string $cookieName,
        private string $cookieExpire,
        private UrlGeneratorInterface $urlGenerator,
        private string $fallbackRoute
    ) {
    }

    /**
     * @return Response usually, action classes should not return a Response object, but should return the one
     *                  given for the Presentation layer
     */
    public function run(?string $refererUrl): Response
    {
        $refererRoute = null;
        $newLocale = $this->switchTask->run();

        if (!is_null($refererUrl)) {
            $refererRoute = $this->routeByUrlTask->run($refererUrl);
        }

        $redirectUrl = $this->urlGenerator->generate(
            !is_null($refererRoute) ? $refererRoute['route']['_route'] : $this->fallbackRoute,
            !is_null($refererRoute) ? $refererRoute['parameters'] : [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->makeResponseWithLocaleCookie($redirectUrl, $newLocale);
    }

    private function makeResponseWithLocaleCookie(string $redirectUrl, string $newLocale): Response
    {
        $localeCookie = new Cookie($this->cookieName, $newLocale, $this->cookieExpire);

        $response = new RedirectResponse($redirectUrl);

        $response->headers->setCookie($localeCookie);

        return $response;
    }
}
