<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Action;

use App\Container\User\Task\MarkUserEmailVerifiedTask;
use App\Ship\Parent\Action;
use Psr\Log\LoggerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyUserEmailAction extends Action
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MarkUserEmailVerifiedTask $markUserEmailVerifiedTask,
        private LoggerInterface $authLogger
    ) {
    }

    public function run(string $signedUrl, int $userId, string $userEmail): ?string
    {
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($signedUrl, (string) $userId, $userEmail);
        } catch (VerifyEmailExceptionInterface $e) {
            $this->authLogger->warning($e->getReason());

            return $e->getReason();
        }

        $this->markUserEmailVerifiedTask->run($userId);

        return null;
    }
}
