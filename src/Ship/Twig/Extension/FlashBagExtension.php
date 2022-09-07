<?php

declare(strict_types=1);

namespace App\Ship\Twig\Extension;

use App\Ship\Task\GetFlashBagNameTask;
use Symfony\Bridge\Twig\AppVariable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FlashBagExtension extends AbstractExtension
{
    public function __construct(
        private GetFlashBagNameTask $bagNameTask
    )
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('flashError', [$this, 'getFlashBagError']),
            new TwigFilter('flashField', [$this, 'getFlashBagField'])
        ];
    }

    public function getFlashBagError(AppVariable $appVariable, string $key, bool $peek = false): string
    {
        return $this->getFlashBag(GetFlashBagNameTask::ERROR, $appVariable, $key, $peek);
    }

    private function getFlashBag(string $prefix, AppVariable $appVariable, string $key, bool $peek = false): string
    {
        $errorName = $this->bagNameTask->run($key, $prefix);

        if ($peek)
            $errors = $appVariable->getSession()->getFlashBag()->peek($errorName);
        else
            $errors = $appVariable->getFlashes($errorName);

        if (empty(array_filter($errors))) return '';

        return array_pop($errors);
    }

    public function getFlashBagField(AppVariable $appVariable, string $key, bool $peek = false): string
    {
        return $this->getFlashBag(GetFlashBagNameTask::FIELD, $appVariable, $key, $peek);
    }
}