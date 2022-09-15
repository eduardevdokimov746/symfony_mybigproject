<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageResize;

use App\Ship\Contract\ImageResize;
use DomainException;
use GdImage;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractImageResizeService implements ImageResize
{
    private function __construct(
        private SplFileInfo $origin,
        private string $extension
    ) {
        if ($this->origin->isDir()) {
            throw new RuntimeException('The file cannot be a directory');
        }
    }

    final public static function shouldResize(SplFileInfo $file): bool
    {
        return self::getSize($file, 'width') > static::width()
            || self::getSize($file, 'height') > static::height();
    }

    final public static function createFromUploadedFile(UploadedFile $file): static
    {
        return static::create($file, $file->guessExtension());
    }

    final public static function create(SplFileInfo $file, string $extension): static
    {
        return new static($file, $extension);
    }

    final public function run(): SplFileInfo
    {
        $originGd = imagecreatefromstring(file_get_contents($this->getOrigin()->getPathname()));

        $cropGd = $this->crop($originGd);

        $resizeGd = imagescale(
            $cropGd,
            static::width(),
            static::height(),
            IMG_BICUBIC
        );

        $tmpFile = $this->createTemporaryFile();

        $this->write($tmpFile, $resizeGd);

        return new SplFileInfo($tmpFile);
    }

    final protected function getOrigin(): SplFileInfo
    {
        return $this->origin;
    }

    protected static function width(): int
    {
        throw new RuntimeException('The method "'.__METHOD__.'" must be defined in the child class');
    }

    protected static function height(): int
    {
        throw new RuntimeException('The method "'.__METHOD__.'" must be defined in the child class');
    }

    abstract protected function getRectangleForCrop(): array;

    final protected static function getSize(SplFileInfo $file, string $key): int
    {
        return match ($key) {
            'width' => getimagesize($file->getPathname())[0],
            'height' => getimagesize($file->getPathname())[1],
            default => throw new DomainException(
                sprintf('Key %s is not supported. Available options %s.', $key, implode(',', ['width', 'height']))
            )
        };
    }

    private function crop(GdImage $originGd): GdImage
    {
        return imagecrop($originGd, $this->getRectangleForCrop());
    }

    private function createTemporaryFile(): string
    {
        return tempnam(sys_get_temp_dir(), self::TMP_PREFIX).'.'.ltrim($this->extension, '.');
    }

    private function write(string $tmpFile, GdImage $resize): void
    {
        switch ($this->extension) {
            case 'png':
                imagepng($resize, $tmpFile, quality: 0);
                break;
            case 'jpg':
                imagejpeg($resize, $tmpFile, quality: 100);
                break;
            case 'gif':
                imagegif($resize, $tmpFile);
                break;
            default:
                $msg = sprintf(
                    '%s file extension is not supported. %s types available',
                    ucfirst($this->extension),
                    implode(',', ['png', 'jpg', 'gif'])
                );

                throw new DomainException($msg);
        }
    }
}
