<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Action;

use App\Container\AuthSection\ResetPassword\Data\DTO\GenerateAndSendResetPasswordTokenDTO;
use App\Container\AuthSection\ResetPassword\Task\SendEmailResetPasswordTask;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Container\User\Task\FindUserByVerifiedEmailTask;
use App\Ship\Parent\Action;
use Psr\Log\LoggerInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class GenerateAndSendResetPasswordTokenAction extends Action
{
    public function __construct(
        private FindUserByVerifiedEmailTask $findUserByVerifiedEmail,
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private SendEmailResetPasswordTask $sendEmailResetPassword,
        private LoggerInterface $resetPasswordLogger
    ) {
    }

    public function run(GenerateAndSendResetPasswordTokenDTO $dto): string|ResetPasswordToken
    {
        if (is_string($user = $this->getUser($dto->email))) {
            return $user;
        }

        if (is_string($resetToken = $this->generateResetPasswordToken($user))) {
            return $resetToken;
        }

        $this->sendEmailResetPassword->run($user->getEmail(), $user->getUserIdentifier(), $resetToken);

        return $resetToken;
    }

    private function getUser(string $email): string|User
    {
        try {
            return $this->findUserByVerifiedEmail->run($email);
        } catch (UserNotFoundException $e) {
            $this->resetPasswordLogger->info($e->getMessage());

            return $e->getMessage();
        }
    }

    private function generateResetPasswordToken(User $user): string|ResetPasswordToken
    {
        try {
            return $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->resetPasswordLogger->info($e->getReason());

            return $e->getReason();
        }
    }
}
