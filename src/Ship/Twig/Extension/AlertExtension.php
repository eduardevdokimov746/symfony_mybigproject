<?php

declare(strict_types=1);

namespace App\Ship\Twig\Extension;

use LogicException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AlertExtension extends AbstractExtension
{
    private const AVAILABLE_TYPES = ['success', 'danger', 'info', 'warning'];

    public function __construct(
        private Environment $twig,
        private TranslatorInterface $translator
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('alert_*', [$this, 'showAlert']),
        ];
    }

    public function showAlert(string $type, string $message = ''): void
    {
        if (!in_array($type, self::AVAILABLE_TYPES, true)) {
            $msg = sprintf('Available types [%s], "%s" given', implode(',', self::AVAILABLE_TYPES), $type);

            throw new LogicException($msg);
        }

        if ('' === $message) {
            $message = 'success' === $type ? 'success_message_default' : 'error_message_default';
        }

        $message = $this->translator->trans($message, domain: 'admin_base');

        $this->twig->display('@ship/extension/alert.html.twig', [
            'message' => $message,
            'type' => $type,
        ]);
    }
}
