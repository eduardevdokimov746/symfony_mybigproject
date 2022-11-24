<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageStorage;

enum ImageStorageEnum: string
{
    case Avatar = 'avatar';
    case News = 'news';
}
