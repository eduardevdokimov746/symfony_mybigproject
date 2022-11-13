<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageResize;

class AvatarImageResizeService extends AbstractImageResizeService
{
    public const WIDTH = 50;
    public const HEIGHT = 50;

    protected function getRectangleForCrop(): array
    {
        $originWidth = self::getSize($this->getOrigin(), 'width');
        $originHeight = self::getSize($this->getOrigin(), 'height');

        $cropSize = min($originWidth, $originHeight);

        if ($originHeight === $cropSize) {
            $x = (int) round(($originWidth - $cropSize) / 2);
        } else {
            $y = (int) round(($originHeight - $cropSize) / 2);
        }

        return [
            'x' => $x ?? 0,
            'y' => $y ?? 0,
            'width' => $cropSize,
            'height' => $cropSize,
        ];
    }

    protected static function width(): int
    {
        return self::WIDTH;
    }

    protected static function height(): int
    {
        return self::HEIGHT;
    }
}
