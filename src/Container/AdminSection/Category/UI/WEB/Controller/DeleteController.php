<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\UI\WEB\Controller;

use App\Container\AdminSection\Category\Action\DeleteCategoryByIdAction;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/{id}',
    name: 'delete',
    methods: 'DELETE',
    requirements: ['id' => '\d+']
)]
class DeleteController extends Controller
{
    public function __construct(
        private DeleteCategoryByIdAction $deleteCategoryByIdAction
    ) {
    }

    public function __invoke(int $id): Response
    {
        if ($this->deleteCategoryByIdAction->run($id)) {
            $this->addFlash('success', '');
        }

        return $this->redirectToRoute('admin.categories.index');
    }
}
