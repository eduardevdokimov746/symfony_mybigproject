<?php

namespace App\Container\AuthSection\ResetPassword\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/',
    name: 'request',
    methods: ['GET']
)]
class RequestController extends Controller
{
    public function __invoke(): Response
    {
        return $this->render('@reset_password/request.html.twig');
    }
}