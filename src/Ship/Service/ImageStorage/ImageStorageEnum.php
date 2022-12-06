<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageStorage;

enum ImageStorageEnum: string
{
    case Avatar = 'avatar';
    case News = 'news';

    /**
     * @return non-empty-list<string>
     */
    public static function getAllValues(): array
    {
        return array_map(static fn (ImageStorageEnum $enum): string => $enum->value, ImageStorageEnum::cases());
    }
}
