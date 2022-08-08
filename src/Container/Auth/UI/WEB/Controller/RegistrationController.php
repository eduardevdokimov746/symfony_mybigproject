<?php

namespace App\Container\Auth\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;

#[Route(
    path: '/registration',
    name: 'registration',
    methods: ['GET', 'POST']
)]
class RegistrationController extends Controller
{
    public function __invoke(LocaleSwitcher $localeSwitcher): Response
    {
        return $this->render('@auth/registration.html.twig');
    }
}