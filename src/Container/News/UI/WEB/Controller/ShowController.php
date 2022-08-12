<?php

namespace App\Container\News\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RequestContext;

#[Route(
    path: '/{slug}',
    name: 'show',
    methods: 'GET',
    requirements: ['slug' => '[a-z0-9]+']
)]
class ShowController extends Controller
{
    public function __construct(LoggerInterface $logger)
    {
    }

    public function __invoke(string $slug, RequestContext $requestContext): Response
    {
        return $this->render('@news/show.html.twig');
    }
}