<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\UI\WEB\Controller;

use App\Container\AdminSection\User\FormType\UserFormType;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/create',
    name: 'create',
    methods: ['GET', 'POST']
)]
class CreateController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(UserFormType::class, options: ['method' => 'POST']);

        if ($this->validateAndSaveForm($form->handleRequest($request))) {
            $this->addFlash('success', '');

            return $this->redirectToRoute('admin.users.create');
        }

        return $this->render('@admin_user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
