<?php

namespace App\Container\AuthSection\Auth\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(
    path: '/login',
    name: 'login',
    methods: ['GET', 'POST']
)]
class LoginController extends Controller
{
    public function __invoke(
        AuthenticationUtils $authenticationUtils,
        #[Autowire('%auth_csrf_token_id%')]
        string              $authCsrfTokenId
    ): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED'))
            return $this->redirectToRoute('home.main');

        return $this->render('@auth/login.html.twig', [
            'auth_csrf_token_id' => $authCsrfTokenId,
            'error'              => $authenticationUtils->getLastAuthenticationError(),
            'last_username'      => $authenticationUtils->getLastUsername()
        ]);
    }
}