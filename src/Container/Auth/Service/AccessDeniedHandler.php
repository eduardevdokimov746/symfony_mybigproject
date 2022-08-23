<?php

namespace App\Container\Auth\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public const REDIRECT_TO_HOME = [
        'auth.login',
        'auth.registration'
    ];

    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        $currentRoute = $request->attributes->get('_route');

        foreach (self::REDIRECT_TO_HOME as $route) {
            if ($currentRoute === $route) {
                return new RedirectResponse($this->urlGenerator->generate('home.main'));
            }
        }

        return null;
    }
}