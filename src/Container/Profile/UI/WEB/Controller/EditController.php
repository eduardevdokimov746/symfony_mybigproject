<?php

declare(strict_types=1);

namespace App\Container\Profile\UI\WEB\Controller;

use App\Container\Profile\Action\UpdateProfileFromAuthUserAction;
use App\Container\Profile\Data\DTO\UpdateProfileFromAuthUserDTO;
use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/edit',
    name: 'edit',
    methods: ['GET', 'PATCH']
)]
#[IsGranted('IS_AUTHENTICATED')]
class EditController extends Controller
{
    public function __construct(
        private UpdateProfileFromAuthUserAction $updateProfileFromAuthUserAction
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if (
            $request->isMethod('PATCH')
            && $this->isValid($dto = $this->createDTO(UpdateProfileFromAuthUserDTO::class))
        ) {
            $this->updateProfileFromAuthUserAction->run($dto);

            $this->addFlash('success', '');

            return $this->redirectBack();
        }

        return $this->render('@profile/edit_profile.html.twig');
    }
}
