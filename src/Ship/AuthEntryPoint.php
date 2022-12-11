<?php

declare(strict_types=1);

namespace App\Ship;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $route = str_contains($request->getPathInfo(), 'admin') ? 'admin.auth.login' : 'auth.login';

        return new RedirectResponse($this->urlGenerator->generate($route));
    }
}
