<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Integration\Action;

use App\Container\AuthSection\Auth\Action\VerifyUserEmailAction;
use App\Container\User\Task\MarkUserEmailVerifiedTask;
use App\Ship\Parent\Test\KernelTestCase;
use Psr\Log\LoggerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\InvalidSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyUserEmailActionTest extends KernelTestCase
{
    public function testRunExpectNull(): void
    {
        $markUserEmailVerifiedTask = self::getContainer()->get(MarkUserEmailVerifiedTask::class);
        $verifyEmailHelper = self::createStub(VerifyEmailHelperInterface::class);
        $logger = self::createStub(LoggerInterface::class);

        $verifyUserEmailAction = new VerifyUserEmailAction($verifyEmailHelper, $markUserEmailVerifiedTask, $logger);

        $result = $verifyUserEmailAction->run('signedUrl', 1, 'user@mail.com');

        $user = $this->findUserFromDB();

        self::assertTrue($user->isEmailVerified());
        self::assertNull($result);
    }

    public function testRunExpectString(): void
    {
        $markUserEmailVerifiedTask = self::getContainer()->get(MarkUserEmailVerifiedTask::class);
        $verifyEmailHelper = self::createStub(VerifyEmailHelperInterface::class);
        $exception = new InvalidSignatureException();
        $verifyEmailHelper->method('validateEmailConfirmation')->willThrowException($exception);
        $logger = self::createStub(LoggerInterface::class);

        $verifyUserEmailAction = new VerifyUserEmailAction($verifyEmailHelper, $markUserEmailVerifiedTask, $logger);

        $result = $verifyUserEmailAction->run('badSignedUrl', 1, 'user@mail.com');

        self::assertIsString($result);
    }
}
