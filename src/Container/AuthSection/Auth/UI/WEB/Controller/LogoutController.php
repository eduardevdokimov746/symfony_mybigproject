<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\UI\WEB\Controller;

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
        // noop
    }
}
