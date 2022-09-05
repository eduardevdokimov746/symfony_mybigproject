<?php

namespace App\Container\AuthSection\Auth\Action;

use App\Container\User\Task\MarkUserEmailVerifiedTask;
use App\Ship\Parent\Action;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyUserEmailAction extends Action
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MarkUserEmailVerifiedTask  $markUserEmailVerifiedTask
    )
    {
    }

    public function run(string $signedUrl, int $userId, string $userEmail): ?string
    {
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($signedUrl, $userId, $userEmail);
        } catch (VerifyEmailExceptionInterface $e) {
            return $e->getReason();
        }

        $this->markUserEmailVerifiedTask->run($userId);

        return null;
    }
}