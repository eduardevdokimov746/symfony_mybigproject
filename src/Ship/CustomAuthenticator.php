<?php

declare(strict_types=1);

namespace App\Ship;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class CustomAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserLoaderInterface $userLoader,
        private AuthenticationSuccessHandlerInterface $successHandler,
        private array $options
    ) {
        $this->options = array_merge([
            'username_parameter' => '_username',
            'password_parameter' => '_password',
            'check_path' => '/login_check',
            'csrf_parameter' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ], $this->options);

        $this->successHandler->setFirewallName('main');
    }

    public function authenticate(Request $request): Passport
    {
        /** @var string $userName */
        $userName = $request->get($this->options['username_parameter']);

        /** @var string $password */
        $password = $request->get($this->options['password_parameter']);

        /** @var string $csrfToken */
        $csrfToken = $request->get($this->options['csrf_parameter']);

        $userBadge = new UserBadge($userName, $this->userLoader->loadUserByIdentifier(...));

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $userName);

        return new Passport($userBadge, new PasswordCredentials($password), [
            new RememberMeBadge(),
            new CsrfTokenBadge($this->options['csrf_token_id'], $csrfToken),
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->successHandler->onAuthenticationSuccess($request, $token);

//        return new RedirectResponse($this->urlGenerator->generate($this->options['default_target_path']));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate($this->options['check_path']);
    }
}
