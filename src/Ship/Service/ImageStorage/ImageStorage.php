<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageStorage;

use SplFileInfo;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageStorage
{
    public function __construct(
        private Filesystem $filesystem,
        private Packages $asset
    ) {
    }

    public function store(SplFileInfo $file, ImageStorageEnum $imageStorageEnum): string
    {
        $fileName = $this->makeFileName($file);
        $replacePath = ltrim($this->asset->getUrl($fileName, $imageStorageEnum->value), '/');

        if (is_uploaded_file($file->getPathname())) {
            move_uploaded_file($file->getPathname(), $replacePath);
        } else {
            $this->filesystem->rename($file->getPathname(), $replacePath);
        }

        return $fileName;
    }

    public function remove(string $image, ImageStorageEnum $imageStorageEnum): void
    {
        $this->filesystem->remove(ltrim($this->asset->getUrl($image, $imageStorageEnum->value), '/'));
    }

    private function makeFileName(SplFileInfo $file): string
    {
        $extension = $file instanceof UploadedFile ? $file->guessExtension() : $file->getExtension();

        return uniqid().(!$extension ?: ".{$extension}");
    }
}
