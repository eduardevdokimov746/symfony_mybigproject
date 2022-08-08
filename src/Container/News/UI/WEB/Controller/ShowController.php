<?php

namespace App\Container\News\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/{slug}',
    name: 'show',
    methods: 'GET',
    requirements: ['slug' => '[a-z0-9]+']
)]
class ShowController extends Controller
{
    public function __invoke(string $slug): Response
    {
        return $this->render('@news/show.html.twig');
    }
}