<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageStorage;

use SplFileInfo;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageStorage
{
    public function __construct(
        private Filesystem $filesystem,
        private Packages $asset,
        #[Autowire('%app_public_dir%')]
        private string $publicDir
    ) {
    }

    public function store(SplFileInfo $file, ImageStorageEnum $imageStorageEnum): string
    {
        $fileName = $this->makeFileName($file);
        $replacePath = $this->publicDir.$this->asset->getUrl($fileName, $imageStorageEnum->value);

        if (is_uploaded_file($file->getPathname())) {
            move_uploaded_file($file->getPathname(), $replacePath);
        } else {
            $this->filesystem->rename($file->getPathname(), $replacePath);
        }

        return $fileName;
    }

    public function remove(string $image, ImageStorageEnum $imageStorageEnum): void
    {
        $this->filesystem->remove($this->publicDir.$this->asset->getUrl($image, $imageStorageEnum->value));
    }

    private function makeFileName(SplFileInfo $file): string
    {
        $extension = $file instanceof UploadedFile ? (string) $file->guessExtension() : $file->getExtension();

        $extension = '' !== $extension ? '.'.$extension : '';

        return uniqid().$extension;
    }
}
