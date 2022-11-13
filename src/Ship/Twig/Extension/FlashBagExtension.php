<?php

declare(strict_types=1);

namespace App\Ship\Twig\Extension;

use App\Ship\Enum\FlashBagNameEnum;
use Symfony\Bridge\Twig\AppVariable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @codeCoverageIgnore
 */
class FlashBagExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('flashError', [$this, 'getFlashBagError']),
            new TwigFilter('flashField', [$this, 'getFlashBagField']),
        ];
    }

    /**
     * @return string[]
     */
    public function getFlashBagError(AppVariable $appVariable, string $key, bool $peek = false): array
    {
        return $this->getFlashBag(FlashBagNameEnum::ERROR, $appVariable, $key, $peek);
    }

    public function getFlashBagField(AppVariable $appVariable, string $key, bool $peek = false): string
    {
        $fields = $this->getFlashBag(FlashBagNameEnum::FIELD, $appVariable, $key, $peek);

        return 0 === count($fields) ? '' : array_pop($fields);
    }

    /**
     * @return string[]
     */
    private function getFlashBag(FlashBagNameEnum $flashBagNameEnum, AppVariable $appVariable, string $key, bool $peek = false): array
    {
        $bagName = $flashBagNameEnum->getNameFor($key);

        if ($peek) {
            $values = $appVariable->getSession()?->getFlashBag()->peek($bagName) ?? [];
        } else {
            $values = $appVariable->getFlashes($bagName);
        }

        return array_filter($values);
    }
}
