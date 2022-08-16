<?php

namespace App\Container\Auth\UI\WEB\Controller;

use App\Container\Auth\Validator\LoginValidator;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/login',
    name: 'login',
    methods: ['GET', 'POST']
)]
class LoginController extends Controller
{
    public function __invoke(
        Request        $request,
        LoginValidator $validator,
    ): Response
    {
        if ($validator->isValid())
            dd($validator->getValidated());

        return $this->render('@auth/login.html.twig');
    }
}