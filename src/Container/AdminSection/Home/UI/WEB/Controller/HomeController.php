<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Home\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/',
    name: 'main',
    methods: 'GET'
)]
class HomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return $this->render('@admin_home/home.html.twig');
    }
}
