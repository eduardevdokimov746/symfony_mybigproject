<?php

declare(strict_types=1);

namespace App\Ship\Enum;

enum FlashBagNameEnum: string
{
    case ERROR = 'error.';
    case FIELD = 'field.';

    public function getNameFor(string $key): string
    {
        return $this->value.$key;
    }
}
