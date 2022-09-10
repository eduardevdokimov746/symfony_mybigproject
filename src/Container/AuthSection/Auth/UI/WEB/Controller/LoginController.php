<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\UI\WEB\Controller;

use App\Ship\Attribute\OnlyGuest;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(
    path: '/login',
    name: 'login',
    methods: ['GET', 'POST']
)]
#[OnlyGuest]
class LoginController extends Controller
{
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('@auth/login.html.twig', [
            'auth_csrf_token_id' => $this->getParameter('auth_csrf_token_id'),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }
}
