<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\UI\WEB\Controller;

use App\Container\AdminSection\Category\Action\GetAllCategoriesWithPaginationAction;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/',
    name: 'index',
    methods: 'GET'
)]
class IndexController extends Controller
{
    public function __construct(
        private GetAllCategoriesWithPaginationAction $getAllCategoriesWithPaginationAction
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $categories = $this->getAllCategoriesWithPaginationAction->run($request->query->get('p'));

        return $this->render('@admin_category/index.html.twig', ['categories' => $categories]);
    }
}
