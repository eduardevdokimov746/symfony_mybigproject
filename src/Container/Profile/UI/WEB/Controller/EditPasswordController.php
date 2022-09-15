<?php

declare(strict_types=1);

namespace App\Container\Profile\UI\WEB\Controller;

use App\Container\Profile\Action\ChangePasswordFromAuthUserAction;
use App\Container\Profile\Validator\EditPasswordValidator;
use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/edit-password',
    name: 'edit_password',
    methods: ['GET', 'POST']
)]
#[IsGranted('IS_AUTHENTICATED')]
class EditPasswordController extends Controller
{
    public function __construct(
        private ChangePasswordFromAuthUserAction $changePasswordFromAuthUserAction
    ) {
    }

    public function __invoke(EditPasswordValidator $validator): Response
    {
        if ($validator->isValid()) {
            $this->changePasswordFromAuthUserAction->run($validator->getValidated()['newPlainPassword']);

            $this->addFlash('success', '');

            return $this->redirectBack();
        }

        return $this->render('@profile/edit_password.html.twig');
    }
}
