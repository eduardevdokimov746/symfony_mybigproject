<?php

namespace App\Container\AuthSection\ResetPassword\Task;

use App\Ship\Parent\Task;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use Twig\Environment;

class SendEmailResetPasswordTask extends Task
{
    private const ROUTE    = 'reset_password.reset';
    private const TEMPLATE = '@email/reset_password.html.twig';

    public function __construct(
        private TranslatorInterface   $translator,
        private Environment           $twig,
        private MailerInterface       $mailer,
        private Packages              $asset,
        private LoggerInterface       $resetPasswordLogger,
        private LoggerInterface       $logger,
        private UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function run(string $recipientEmail, string $userIdentifier, ResetPasswordToken $token): void
    {
        try {
            $this->mailer->send((new Email())
                ->subject($this->getSubject())
                ->to($recipientEmail)
                ->embedFromPath($this->getLogoPath(), 'logo')
                ->html($this->getHtmlBody($userIdentifier, $this->getSignedUrl($token)))
            );
        } catch (TransportExceptionInterface $e) {
            $this->resetPasswordLogger->warning('Email send', ['debug' => $e->getDebug()]);
            $this->logger->critical('Error sending password reset mail');
        }

        $this->resetPasswordLogger->debug('Email reset password sent', ['token' => $token->getToken()]);
    }

    private function getSubject(): string
    {
        return $this->translator->trans('reset_password.subject', domain: 'email');
    }

    private function getLogoPath(): string
    {
        return ltrim($this->asset->getUrl('logo.png', 'img'), '/');
    }

    private function getHtmlBody(string $userIdentifier, string $signedUrl): string
    {
        return $this->twig->render(
            self::TEMPLATE,
            [
                'user_identifier' => $userIdentifier,
                'signed_url'      => $signedUrl
            ]
        );
    }

    private function getSignedUrl(ResetPasswordToken $token): string
    {
        return $this->urlGenerator->generate(self::ROUTE, ['token' => $token->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}