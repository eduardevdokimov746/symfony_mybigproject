<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Integration\Action;

use App\Container\AuthSection\Auth\Action\SendEmailVerificationAction;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Test\KernelTestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Twig\Environment;

class SendEmailVerificationActionTest extends KernelTestCase
{
    private User $user;
    private SendEmailVerificationAction $sendEmailVerificationAction;

    protected function setUp(): void
    {
        $translator = self::getContainer()->get(TranslatorInterface::class);
        $twig = self::getContainer()->get(Environment::class);
        $mailer = self::getContainer()->get(MailerInterface::class);
        $verifyEmailHelper = self::getContainer()->get(VerifyEmailHelperInterface::class);
        $packages = self::getContainer()->get(Packages::class);
        $logger = self::createStub(LoggerInterface::class);

        $this->sendEmailVerificationAction = new SendEmailVerificationAction(
            $translator,
            $twig,
            $mailer,
            $verifyEmailHelper,
            $packages,
            $logger,
            $logger
        );

        $this->user = self::findUserFromDB();
    }

    public function testRun(): void
    {
        $this->sendEmailVerificationAction->run($this->user);

        self::assertEmailCount(1);
    }

    public function testGetFakeSignatureComponents(): void
    {
        $result = $this->sendEmailVerificationAction->getFakeSignatureComponents($this->user);

        self::assertInstanceOf(VerifyEmailSignatureComponents::class, $result);
    }
}
