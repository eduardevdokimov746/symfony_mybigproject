<?php

declare(strict_types=1);

namespace App\Ship\Helper;

use App\Ship\Contract\ImageResize;
use App\Ship\Exception\FileException;
use Exception;
use GdImage;

class File
{
    private const COMMON_TMP_PREFIXES = [
        ImageResize::TMP_PREFIX,
    ];

    /**
     * @return list<int|string>
     */
    public static function getImageSize(string $file): array
    {
        self::checkFileExists($file);

        if (false === $imageSize = getimagesize($file)) {
            throw new FileException('Error getting image size');
        }

        return $imageSize;
    }

    /**
     * @param array{x: int, y: int, width: int, height: int} $rectangle
     */
    public static function imageCrop(GdImage $image, array $rectangle): GdImage
    {
        if (false === $crop = imagecrop($image, $rectangle)) {
            throw new FileException('Image crop error');
        }

        return $crop;
    }

    public static function checkFileExists(string $file): void
    {
        if (!file_exists($file)) {
            throw new FileException('File {'.$file.'} does not exist');
        }
    }

    public static function createTmpFile(string $prefix): string
    {
        if (false === $tmpFile = tempnam(sys_get_temp_dir(), $prefix)) {
            throw new FileException('Create tmp file error');
        }

        return $tmpFile;
    }

    public static function getContent(string $file): string
    {
        self::checkFileExists($file);

        if (false === $content = file_get_contents($file)) {
            throw new FileException('File get content error');
        }

        return $content;
    }

    public static function createImageFromString(string $data): GdImage
    {
        if (false === $image = imagecreatefromstring($data)) {
            throw new FileException('Create image error');
        }

        return $image;
    }

    public static function imageScale(GdImage $image, int $width, int $height = -1, int $mode = IMG_BICUBIC): GdImage
    {
        if (false === $scale = imagescale($image, $width, $height, $mode)) {
            throw new FileException('Image scale error');
        }

        return $scale;
    }

    public static function createTmpImage(string $prefix, int $width, int $height): string
    {
        $file = self::createTmpFile($prefix);

        if (false === $content = imagecreatetruecolor($width, $height)) {
            throw new FileException('Create image error');
        }

        imagepng($content, $file);
        imagedestroy($content);

        return $file;
    }

    /**
     * @param list<string>|string $prefix
     */
    public static function removeTmpFiles(array|string $prefix = []): bool
    {
        try {
            $prefix = is_string($prefix) ? [$prefix] : $prefix;

            $prefixes = implode(',', array_merge(self::COMMON_TMP_PREFIXES, $prefix));

            $pattern = sprintf('%s/{%s}*', sys_get_temp_dir(), $prefixes);

            if (false === $files = glob($pattern, GLOB_NOSORT | GLOB_BRACE)) {
                return false;
            }

            array_map('unlink', $files);

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
