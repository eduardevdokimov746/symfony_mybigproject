<?php

declare(strict_types=1);

namespace App\Ship\ExceptionHandler\Formatter;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;
use Twig\Environment;

class TelegramFormatter implements FormatterInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public function format(LogRecord $record)
    {
        return $this->twig->render('@ship/layout/telegram_report.html.twig', ['log' => $record]);
    }

    public function formatBatch(array $records)
    {
        return $this->format(array_shift($records));
    }
}