<?php

namespace App\Container\Profile\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/',
    name: 'index',
    methods: ['GET']
)]
class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return new Response('profile page');
    }
}