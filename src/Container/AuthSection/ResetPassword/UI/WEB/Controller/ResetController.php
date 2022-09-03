<?php

namespace App\Container\AuthSection\ResetPassword\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/reset/{token?}',
    name: 'reset',
    methods: ['GET'],
    defaults: ['token' => null]
)]
class ResetController extends Controller
{
    public function __invoke(?string $token = null): Response
    {
        return $this->render('@reset_password/reset.html.twig');
    }
}