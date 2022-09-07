<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\UI\WEB\Controller;

use App\Ship\Attribute\OnlyGuest;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route(
    path: '/check-email',
    name: 'check_email',
    methods: 'GET'
)]
#[OnlyGuest]
class CheckEmailController extends Controller
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper
    )
    {
    }

    public function __invoke(): Response
    {
        if (null === ($resetToken = $this->getTokenObjectFromSession()))
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();

        return $this->render('@reset_password/check_email.html.twig', [
            'reset_token' => $resetToken
        ]);
    }
}