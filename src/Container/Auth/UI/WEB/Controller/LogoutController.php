<?php

namespace App\Container\Auth\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/logout',
    name: 'logout',
    methods: ['GET']
)]
class LogoutController extends Controller
{
    public function __invoke()
    {
        # noop
    }
}