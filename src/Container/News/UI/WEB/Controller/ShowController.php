<?php

namespace App\Container\News\UI\WEB\Controller;

use App\Container\News\Exception\NewsNotFoundException;
use App\Ship\Parent\Controller;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(
    path: '/{slug}',
    name: 'show',
    methods: 'GET',
    requirements: ['slug' => '[a-z0-9]+']
)]
class ShowController extends Controller
{
    public function __construct()
    {
    }

    public function __invoke(string $slug, RequestContext $requestContext): Response
    {
        throw new NewsNotFoundException();
        return $this->render('@news/show.html.twig');
    }
}