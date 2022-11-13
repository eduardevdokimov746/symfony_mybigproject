<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Integration\Action;

use App\Container\AuthSection\ResetPassword\Action\RemoveResetTokenAndChangeUserPasswordAction;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Ship\Parent\Test\KernelTestCase;
use Psr\Log\LoggerInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\InvalidResetPasswordTokenException;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class RemoveResetTokenAndChangeUserPasswordActionTest extends KernelTestCase
{
    public function testRunExpectNull(): void
    {
        $user = $this->findUserFromDB();
        $oldPassword = $user->getPassword();

        $resetPasswordHelper = self::createStub(ResetPasswordHelperInterface::class);
        $resetPasswordHelper->method('validateTokenAndFetchUser')->willReturn($user);
        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $logger = self::createStub(LoggerInterface::class);

        $removeResetTokenAndChangeUserPasswordAction = new RemoveResetTokenAndChangeUserPasswordAction(
            $resetPasswordHelper,
            $changeUserPasswordTask,
            $logger
        );

        $result = $removeResetTokenAndChangeUserPasswordAction->run('token', 'new password');

        $userWithNewPassword = $this->findUserFromDB();

        self::assertNull($result);
        self::assertNotSame($oldPassword, $userWithNewPassword->getPassword());
    }

    public function testRunExpectString(): void
    {
        $resetPasswordHelper = self::createStub(ResetPasswordHelperInterface::class);
        $resetPasswordHelper
            ->method('validateTokenAndFetchUser')
            ->willThrowException(new InvalidResetPasswordTokenException())
        ;
        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $logger = self::createStub(LoggerInterface::class);

        $removeResetTokenAndChangeUserPasswordAction = new RemoveResetTokenAndChangeUserPasswordAction(
            $resetPasswordHelper,
            $changeUserPasswordTask,
            $logger
        );

        $result = $removeResetTokenAndChangeUserPasswordAction->run('token', 'new password');

        self::assertIsString($result);
    }
}
