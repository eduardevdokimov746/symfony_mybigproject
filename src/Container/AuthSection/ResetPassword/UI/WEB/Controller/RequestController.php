<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\UI\WEB\Controller;

use App\Container\AuthSection\ResetPassword\Action\GenerateAndSendResetPasswordTokenAction;
use App\Container\AuthSection\ResetPassword\Validator\RequestValidator;
use App\Ship\Attribute\OnlyGuest;
use App\Ship\Parent\Controller;
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
    )
    {
    }

    public function __invoke(RequestValidator $validator): Response
    {
        if ($validator->isValid()) {
            $resetPasswordToken = $this->generateAndSendResetPasswordToken->run($validator->getValidated()['email']);

            if ($resetPasswordToken instanceof ResetPasswordToken)
                $this->setTokenObjectInSession($resetPasswordToken);

            return $this->redirectToRoute('reset_password.check_email');
        }

        return $this->render('@reset_password/request.html.twig');
    }
}