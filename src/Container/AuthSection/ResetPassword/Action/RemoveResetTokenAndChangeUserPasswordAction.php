<?php

namespace App\Container\AuthSection\ResetPassword\Action;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Ship\Parent\Action;
use Psr\Log\LoggerInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class RemoveResetTokenAndChangeUserPasswordAction extends Action
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private ChangeUserPasswordTask       $changeUserPasswordTask,
        private LoggerInterface              $resetPasswordLogger
    )
    {
    }

    public function run(string $token, string $password): ?string
    {
        if (is_string($user = $this->validateTokenAndFetchUser($token))) return $user;

        $this->resetPasswordHelper->removeResetRequest($token);

        $this->changeUserPasswordTask->run($user->getId(), $password);

        $this->resetPasswordLogger->debug('Password reset completed successfully');

        return null;
    }

    private function validateTokenAndFetchUser(string $token): string|User
    {
        try {
            return $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->resetPasswordLogger->info($e->getReason());
            return $e->getReason();
        }
    }
}