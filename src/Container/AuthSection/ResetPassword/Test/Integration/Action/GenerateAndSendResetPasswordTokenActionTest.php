<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Integration\Action;

use App\Container\AuthSection\ResetPassword\Action\GenerateAndSendResetPasswordTokenAction;
use App\Container\AuthSection\ResetPassword\Task\SendEmailResetPasswordTask;
use App\Container\User\Task\FindUserByVerifiedEmailTask;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class GenerateAndSendResetPasswordTokenActionTest extends KernelTestCase
{
    private GenerateAndSendResetPasswordTokenAction $generateAndSendResetPasswordTokenAction;

    protected function setUp(): void
    {
        $findUserByVerifiedEmailTask = self::getContainer()->get(FindUserByVerifiedEmailTask::class);
        $resetPasswordHelper = self::getContainer()->get(ResetPasswordHelperInterface::class);
        $sendEmailResetPasswordTask = self::getContainer()->get(SendEmailResetPasswordTask::class);
        $logger = $this->createStub(LoggerInterface::class);

        $this->generateAndSendResetPasswordTokenAction = new GenerateAndSendResetPasswordTokenAction(
            $findUserByVerifiedEmailTask,
            $resetPasswordHelper,
            $sendEmailResetPasswordTask,
            $logger
        );
    }

    public function testRunExpectToken(): void
    {
        $result = $this->generateAndSendResetPasswordTokenAction->run('admin@mail.com');

        $this->assertInstanceOf(ResetPasswordToken::class, $result);
        $this->assertEmailCount(1);
    }

    public function testRunExpectString(): void
    {
        $result = $this->generateAndSendResetPasswordTokenAction->run('ens@mail.com');

        $this->assertIsString($result);
    }
}
