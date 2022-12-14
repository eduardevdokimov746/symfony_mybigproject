<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Integration\Action;

use App\Container\AuthSection\ResetPassword\Action\GenerateAndSendResetPasswordTokenAction;
use App\Container\AuthSection\ResetPassword\Data\DTO\GenerateAndSendResetPasswordTokenDTO;
use App\Container\AuthSection\ResetPassword\Task\SendEmailResetPasswordTask;
use App\Container\User\Task\FindUserByVerifiedEmailTask;
use App\Ship\Parent\Test\KernelTestCase;
use Psr\Log\LoggerInterface;
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
        $logger = self::createStub(LoggerInterface::class);

        $this->generateAndSendResetPasswordTokenAction = new GenerateAndSendResetPasswordTokenAction(
            $findUserByVerifiedEmailTask,
            $resetPasswordHelper,
            $sendEmailResetPasswordTask,
            $logger
        );
    }

    public function testRunExpectToken(): void
    {
        $result = $this->generateAndSendResetPasswordTokenAction->run(
            GenerateAndSendResetPasswordTokenDTO::create(['email' => 'admin@mail.com'])
        );

        self::assertInstanceOf(ResetPasswordToken::class, $result);
        self::assertEmailCount(1);
    }

    public function testRunExpectString(): void
    {
        $result = $this->generateAndSendResetPasswordTokenAction->run(
            GenerateAndSendResetPasswordTokenDTO::create(['email' => 'ens@mail.com'])
        );

        self::assertIsString($result);
    }
}
