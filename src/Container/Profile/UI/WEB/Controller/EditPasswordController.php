<?php

declare(strict_types=1);

namespace App\Container\Profile\UI\WEB\Controller;

use App\Container\Profile\Action\ChangePasswordAuthUserAction;
use App\Container\Profile\Data\DTO\ChangePasswordAuthUserDTO;
use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
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
        private ChangePasswordAuthUserAction $changePasswordFromAuthUserAction
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if (
            $request->isMethod('POST')
            && $this->isValid($dto = $this->createDTO(ChangePasswordAuthUserDTO::class))
        ) {
            $this->changePasswordFromAuthUserAction->run($dto);

            $this->addFlash('success', '');

            return $this->redirectBack();
        }

        return $this->render('@profile/edit_password.html.twig');
    }
}
