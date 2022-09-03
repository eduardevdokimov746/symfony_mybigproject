<?php

namespace App\Container\AuthSection\Auth\Listener;

use App\Container\AuthSection\Auth\Event\UserRegisteredEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class SendVerificationEmailListener
{
    public function __construct(
        private TranslatorInterface $translator,
        private Environment         $twig,
        private MailerInterface     $mailer
    )
    {
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $recipient = $event->getUser()->getEmail();

        $this->mailer->send((new Email())
            ->subject($this->translator->trans('subject', domain: 'email'))
            ->to($recipient)
            ->html($this->twig->render('@email/confirmation.html.twig', ['recipient' => $recipient]))
        );
    }
}