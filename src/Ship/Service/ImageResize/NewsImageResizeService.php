<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageResize;

class NewsImageResizeService extends AbstractImageResizeService
{
    public const WIDTH = 350;
    public const HEIGHT = 250;

    protected function getRectangleForCrop(): array
    {
        $originWidth = self::getSize($this->getOrigin(), 'width');
        $originHeight = self::getSize($this->getOrigin(), 'height');

        $cropSize = min($originWidth, $originHeight);

        if ($originHeight === $cropSize) {
            $ratio = self::WIDTH / self::HEIGHT;

            $newWidth = $originHeight * $ratio;

            $newHeight = $originHeight;

            while ($newWidth > $originWidth) {
                $newWidth = --$newHeight * $ratio;
            }
        } else {
            $ratio = self::HEIGHT / self::WIDTH;

            $newHeight = $originWidth * $ratio;

            $newWidth = $originWidth;

            while ($newHeight > $originHeight) {
                $newHeight = --$newWidth * $ratio;
            }
        }

        return [
            'x' => (int) round(($originWidth - $newWidth) / 2),
            'y' => (int) round(($originHeight - $newHeight) / 2),
            'width' => (int) $newWidth,
            'height' => (int) $newHeight,
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
