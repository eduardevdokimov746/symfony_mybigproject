<?php

namespace App\Container\AuthSection\Auth\Action;

use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Action;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Twig\Environment;

class SendEmailVerificationAction extends Action
{
    private const VERIFY_EMAIL_ROUTE    = 'auth.verify_user_email';
    private const VERIFY_EMAIL_TEMPLATE = '@email/verification.html.twig';

    public function __construct(
        private TranslatorInterface        $translator,
        private Environment                $twig,
        private MailerInterface            $mailer,
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private Packages                   $asset,
        private LoggerInterface            $authLogger,
        private LoggerInterface            $logger
    )
    {
    }

    public function run(User $user): VerifyEmailSignatureComponents
    {
        $signatureComponents = $this->createSignedUrl($user);

        try {
            $this->mailer->send((new Email())
                ->subject($this->getSubject())
                ->to($user->getEmail())
                ->embedFromPath($this->getLogoPath(), 'logo')
                ->html($this->getHtmlBody($user->getEmail(), $signatureComponents->getSignedUrl()))
            );
        } catch (TransportExceptionInterface $e) {
            $this->authLogger->warning('Email send', ['debug' => $e->getDebug()]);
            $this->logger->critical('Error sending verification mail');
        }

        $this->authLogger->debug('Email verification sent', ['signedUrl' => $signatureComponents->getSignedUrl()]);

        return $signatureComponents;
    }

    private function createSignedUrl(User $user): VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper
            ->generateSignature(
                self::VERIFY_EMAIL_ROUTE,
                $user->getId(),
                $user->getEmail()
            );
    }

    private function getSubject(): string
    {
        return $this->translator->trans('subject', domain: 'email');
    }

    private function getLogoPath(): string
    {
        return ltrim($this->asset->getUrl('logo.png', 'img'), '/');
    }

    private function getHtmlBody(string $recipient, string $signedUrl): string
    {
        return $this->twig->render(
            self::VERIFY_EMAIL_TEMPLATE,
            [
                'recipient'  => $recipient,
                'signed_url' => $signedUrl
            ]
        );
    }

    public function getFakeSignatureComponents(User $user): VerifyEmailSignatureComponents
    {
        return $this->createSignedUrl($user);
    }
}