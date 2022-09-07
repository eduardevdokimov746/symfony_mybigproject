<?php

namespace App\Container\AuthSection\ResetPassword\UI\WEB\Controller;

use App\Container\AuthSection\ResetPassword\Action\RemoveResetTokenAndChangeUserPasswordAction;
use App\Container\AuthSection\ResetPassword\Validator\ResetValidator;
use App\Ship\Attribute\OnlyGuest;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route(
    path: '/reset/{token?}',
    name: 'reset',
    methods: ['GET', 'POST'],
    defaults: ['token' => null]
)]
#[OnlyGuest]
class ResetController extends Controller
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private RemoveResetTokenAndChangeUserPasswordAction $removeResetTokenAndChangeUserPasswordAction,
        private ResetPasswordHelperInterface                $resetPasswordHelper
    )
    {
    }

    public function __invoke(ResetValidator $validator, ?string $token = null): Response
    {
        if ($token && $this->isTokenValid($token)) {
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('reset_password.reset');
        }

        if (null === ($token = $this->getTokenFromSession()))
            return $this->redirectToRoute($this->getParameter('app_default_route'));

        if ($validator->isValid()) {
            $this->removeResetTokenAndChangeUserPasswordAction->run($token, $validator->getValidated()['plainPassword']);

            $this->cleanSessionAfterReset();

            $this->addFlash('success', '');
        }

        return $this->render('@reset_password/reset.html.twig');
    }

    private function isTokenValid(string $token): bool
    {
        try {
            $this->resetPasswordHelper->validateTokenAndFetchUser($token);

            return true;
        } catch (ResetPasswordExceptionInterface) {
            return false;
        }
    }
}