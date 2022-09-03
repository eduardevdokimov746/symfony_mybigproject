<?php

namespace App\Container\AuthSection\ResetPassword\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: 'check-email',
    name: 'check_email',
    methods: ['GET']
)]
class CheckEmailController extends Controller
{
    public function __invoke(): Response
    {
        return $this->render('@reset_password/check_email.html.twig');
    }
}