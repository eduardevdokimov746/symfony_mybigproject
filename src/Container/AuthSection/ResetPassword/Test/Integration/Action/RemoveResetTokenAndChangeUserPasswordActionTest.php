<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Integration\Action;

use App\Container\AuthSection\ResetPassword\Action\RemoveResetTokenAndChangeUserPasswordAction;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyCasts\Bundle\ResetPassword\Exception\InvalidResetPasswordTokenException;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class RemoveResetTokenAndChangeUserPasswordActionTest extends KernelTestCase
{
    public function testRunExpectNull(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        /** @var User $user */
        $user = $entityManager->find(User::class, 1);
        $oldPassword = $user->getPassword();

        $resetPasswordHelper = $this->createStub(ResetPasswordHelperInterface::class);
        $resetPasswordHelper->method('validateTokenAndFetchUser')->willReturn($user);
        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $logger = $this->createStub(LoggerInterface::class);

        $removeResetTokenAndChangeUserPasswordAction = new RemoveResetTokenAndChangeUserPasswordAction(
            $resetPasswordHelper,
            $changeUserPasswordTask,
            $logger
        );

        $result = $removeResetTokenAndChangeUserPasswordAction->run('token', 'new password');

        $userWithNewPassword = $entityManager->find(User::class, 1);

        $this->assertNull($result);
        $this->assertNotSame($oldPassword, $userWithNewPassword->getPassword());
    }

    public function testRunExpectString(): void
    {
        $resetPasswordHelper = $this->createStub(ResetPasswordHelperInterface::class);
        $resetPasswordHelper
            ->method('validateTokenAndFetchUser')
            ->willThrowException(new InvalidResetPasswordTokenException())
        ;
        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $logger = $this->createStub(LoggerInterface::class);

        $removeResetTokenAndChangeUserPasswordAction = new RemoveResetTokenAndChangeUserPasswordAction(
            $resetPasswordHelper,
            $changeUserPasswordTask,
            $logger
        );

        $result = $removeResetTokenAndChangeUserPasswordAction->run('token', 'new password');

        $this->assertIsString($result);
    }
}
