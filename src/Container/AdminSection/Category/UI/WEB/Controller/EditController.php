<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/{id}/edit',
    name: 'edit',
    methods: ['GET', 'PATCH'],
    requirements: ['id' => '\d+']
)]
class EditController extends Controller
{
    public function __invoke(int $id): Response
    {
    }
}
