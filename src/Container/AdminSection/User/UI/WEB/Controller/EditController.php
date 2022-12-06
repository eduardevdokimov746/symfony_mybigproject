<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\UI\WEB\Controller;

use App\Container\AdminSection\User\FormType\UserFormType;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/{id}',
    name: 'edit',
    methods: ['GET', 'PUT'],
    requirements: ['id' => '\d+']
)]
class EditController extends Controller
{
    public function __construct(
        private FindUserByIdTask $findUserByIdTask
    ) {
    }

    public function __invoke(int $id, Request $request): Response
    {
        $user = $this->findUserByIdTask->run($id, true);

        $form = $this->createForm(UserFormType::class, $user, options: [
            'method' => 'PUT',
            'with_delete_avatar' => true,
            'with_preview' => true,
        ]);

        if ($this->validateAndSaveForm($form->handleRequest($request))) {
            $this->addFlash('success', '');

            return $this->redirectBack();
        }

        return $this->render('@admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
