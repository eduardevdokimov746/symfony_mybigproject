<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\UI\WEB\Controller;

use App\Container\AdminSection\User\Action\DeleteUserByIdAction;
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
        private DeleteUserByIdAction $deleteUserByIdAction
    ) {
    }

    public function __invoke(int $id): Response
    {
        if ($this->deleteUserByIdAction->run($id)) {
            $this->addFlash('success', '');
        }

        return $this->redirectToRoute('admin.users.index');
    }
}
