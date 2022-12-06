<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\UI\WEB\Controller;

use App\Container\AdminSection\User\Action\GetAllUsersWithPaginationAction;
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
        private GetAllUsersWithPaginationAction $getAllUsersWithPaginationAction
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $users = $this->getAllUsersWithPaginationAction->run($request->query->get('p'));

        return $this->render('@admin_user/index.html.twig', ['users' => $users]);
    }
}
