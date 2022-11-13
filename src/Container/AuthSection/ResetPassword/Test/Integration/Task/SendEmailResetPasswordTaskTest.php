<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Integration\Task;

use App\Container\AuthSection\ResetPassword\Task\SendEmailResetPasswordTask;
use App\Ship\Parent\Test\KernelTestCase;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use Twig\Environment;

class SendEmailResetPasswordTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $translator = self::getContainer()->get(TranslatorInterface::class);
        $twig = self::getContainer()->get(Environment::class);
        $mailer = self::getContainer()->get(MailerInterface::class);
        $packages = self::getContainer()->get(Packages::class);
        $logger = self::createStub(LoggerInterface::class);
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);

        $sendEmailResetPasswordTask = new SendEmailResetPasswordTask(
            $translator,
            $twig,
            $mailer,
            $packages,
            $logger,
            $logger,
            $urlGenerator
        );

        $resetPasswordToken = new ResetPasswordToken('token', new DateTime('+1 minutes'));

        $sendEmailResetPasswordTask->run('user@mail.com', 'user', $resetPasswordToken);

        self::assertEmailCount(1);
    }
}
