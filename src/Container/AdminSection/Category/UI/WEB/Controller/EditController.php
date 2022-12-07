<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\UI\WEB\Controller;

use App\Container\AdminSection\Category\FormType\CategoryFormType;
use App\Container\AdminSection\Category\Task\FindCategoryByIdTask;
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
        private FindCategoryByIdTask $findCategoryByIdTask
    ) {
    }

    public function __invoke(int $id, Request $request): Response
    {
        $category = $this->findCategoryByIdTask->run($id);

        $form = $this->createForm(CategoryFormType::class, $category, options: ['method' => 'PUT']);

        if ($this->validateAndSaveForm($form->handleRequest($request))) {
            $this->addFlash('success', '');

            return $this->redirectBack();
        }

        return $this->render('@admin_category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }
}
