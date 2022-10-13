<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Integration\Action;

use App\Container\AuthSection\Auth\Action\VerifyUserEmailAction;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\MarkUserEmailVerifiedTask;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyCasts\Bundle\VerifyEmail\Exception\InvalidSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerifyUserEmailActionTest extends KernelTestCase
{
    public function testRunExpectNull(): void
    {
        $markUserEmailVerifiedTask = self::getContainer()->get(MarkUserEmailVerifiedTask::class);
        $verifyEmailHelper = $this->createStub(VerifyEmailHelperInterface::class);
        $logger = $this->createStub(LoggerInterface::class);

        $verifyUserEmailAction = new VerifyUserEmailAction($verifyEmailHelper, $markUserEmailVerifiedTask, $logger);

        $result = $verifyUserEmailAction->run('signedUrl', 1, 'user@mail.com');

        /** @var User $user */
        $user = self::getContainer()->get(EntityManagerInterface::class)->find(User::class, 1);

        $this->assertTrue($user->isEmailVerified());
        $this->assertNull($result);
    }

    public function testRunExpectString(): void
    {
        $markUserEmailVerifiedTask = self::getContainer()->get(MarkUserEmailVerifiedTask::class);
        $verifyEmailHelper = $this->createStub(VerifyEmailHelperInterface::class);
        $exception = new InvalidSignatureException();
        $verifyEmailHelper->method('validateEmailConfirmation')->willThrowException($exception);
        $logger = $this->createStub(LoggerInterface::class);

        $verifyUserEmailAction = new VerifyUserEmailAction($verifyEmailHelper, $markUserEmailVerifiedTask, $logger);

        $result = $verifyUserEmailAction->run('badSignedUrl', 1, 'user@mail.com');

        $this->assertIsString($result);
    }
}
