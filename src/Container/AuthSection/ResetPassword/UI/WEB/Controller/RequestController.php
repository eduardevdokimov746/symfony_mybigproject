<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\UI\WEB\Controller;

use App\Container\AuthSection\ResetPassword\Action\GenerateAndSendResetPasswordTokenAction;
use App\Container\AuthSection\ResetPassword\Data\DTO\GenerateAndSendResetPasswordTokenDTO;
use App\Ship\Attribute\OnlyGuest;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

#[Route(
    path: '/',
    name: 'request',
    methods: ['GET', 'POST']
)]
#[OnlyGuest]
class RequestController extends Controller
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private GenerateAndSendResetPasswordTokenAction $generateAndSendResetPasswordToken
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if (
            $request->isMethod('POST')
            && $this->isValid($dto = $this->createDTO(GenerateAndSendResetPasswordTokenDTO::class))
        ) {
            $resetPasswordToken = $this->generateAndSendResetPasswordToken->run($dto);

            if ($resetPasswordToken instanceof ResetPasswordToken) {
                $this->setTokenObjectInSession($resetPasswordToken);
            }

            return $this->redirectToRoute('reset_password.check_email');
        }

        return $this->render('@reset_password/request.html.twig');
    }
}
