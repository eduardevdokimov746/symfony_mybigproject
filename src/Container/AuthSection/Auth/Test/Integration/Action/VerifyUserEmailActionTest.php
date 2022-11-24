<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Integration\Action;

use App\Container\AuthSection\Auth\Action\VerifyUserEmailAction;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\InvalidSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyUserEmailActionTest extends KernelTestCase
{
    public function testRunExpectNull(): void
    {
        $verifyEmailHelper = self::createStub(VerifyEmailHelperInterface::class);
        $logger = self::createStub(LoggerInterface::class);
        $findUserByIdTask = self::getContainer()->get(FindUserByIdTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $verifyUserEmailAction = new VerifyUserEmailAction($verifyEmailHelper, $logger, $findUserByIdTask);
        $verifyUserEmailAction->setEntityManager($entityManager);

        $result = $verifyUserEmailAction->run('signedUrl', 1, 'user@mail.com');

        $user = self::findUserFromDB();

        self::assertTrue($user->isEmailVerified());
        self::assertNull($result);
    }

    public function testRunExpectString(): void
    {
        $verifyEmailHelper = self::createStub(VerifyEmailHelperInterface::class);
        $exception = new InvalidSignatureException();
        $verifyEmailHelper->method('validateEmailConfirmation')->willThrowException($exception);
        $logger = self::createStub(LoggerInterface::class);
        $findUserByIdTask = self::getContainer()->get(FindUserByIdTask::class);

        $verifyUserEmailAction = new VerifyUserEmailAction($verifyEmailHelper, $logger, $findUserByIdTask);

        $result = $verifyUserEmailAction->run('badSignedUrl', 1, 'user@mail.com');

        self::assertIsString($result);
    }
}
