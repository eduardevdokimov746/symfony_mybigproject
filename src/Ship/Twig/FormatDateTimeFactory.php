<?php

declare(strict_types=1);

namespace App\Ship\Twig;

use DomainException;
use IntlDateFormatter;
use Symfony\Component\Translation\LocaleSwitcher;

class FormatDateTimeFactory
{
    private const RU_PATTERN = "d MMMM y в HH:mm";
    private const EN_PATTERN = "MMMM d, y 'at' H:mm a";

    private string $message = "The current locale '%s' is not support. Available locales [%s].";

    public function __construct(
        private array          $enabledLocales,
        private LocaleSwitcher $localeSwitcher
    )
    {
    }

    public function __invoke(): IntlDateFormatter
    {
        return new IntlDateFormatter($this->getCurrentLocale(), pattern: $this->getPattern());
    }

    private function getCurrentLocale(): string
    {
        return $this->localeSwitcher->getLocale();
    }

    private function getPattern(): string
    {
        return match ($locale = $this->getCurrentLocale()) {
            'ru' => self::RU_PATTERN,
            'en' => self::EN_PATTERN,
            default => throw new DomainException(sprintf($this->message, $locale, implode(',', $this->enabledLocales)))
        };
    }
}