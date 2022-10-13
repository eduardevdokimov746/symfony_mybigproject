<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Integration\Action;

use App\Container\AuthSection\Auth\Action\SendEmailVerificationAction;
use App\Container\User\Entity\Doc\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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
        $logger = $this->createStub(LoggerInterface::class);

        $this->sendEmailVerificationAction = new SendEmailVerificationAction(
            $translator,
            $twig,
            $mailer,
            $verifyEmailHelper,
            $packages,
            $logger,
            $logger
        );

        $this->user = self::getContainer()->get(EntityManagerInterface::class)->find(User::class, 1);
    }

    public function testRun(): void
    {
        $this->sendEmailVerificationAction->run($this->user);

        $this->assertEmailCount(1);
    }

    public function testGetFakeSignatureComponents(): void
    {
        $result = $this->sendEmailVerificationAction->getFakeSignatureComponents($this->user);

        $this->assertInstanceOf(VerifyEmailSignatureComponents::class, $result);
    }
}
