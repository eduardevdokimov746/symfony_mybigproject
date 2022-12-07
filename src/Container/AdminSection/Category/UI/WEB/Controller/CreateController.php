<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\UI\WEB\Controller;

use App\Container\AdminSection\Category\FormType\CategoryFormType;
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
        $form = $this->createForm(CategoryFormType::class, options: ['method' => 'POST']);

        if ($this->validateAndSaveForm($form->handleRequest($request))) {
            $this->addFlash('success', '');

            return $this->redirectToRoute('admin.categories.create');
        }

        return $this->render('@admin_category/create.html.twig', [
            'form' => $form,
        ]);
    }
}
