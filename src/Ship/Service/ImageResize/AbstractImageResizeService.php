<?php

declare(strict_types=1);

namespace App\Ship\Service\ImageResize;

use App\Ship\Contract\ImageResize;
use App\Ship\Helper\File;
use DomainException;
use GdImage;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractImageResizeService implements ImageResize
{
    private SplFileInfo $origin;
    private string $extension;

    final private function __construct(SplFileInfo $origin, string $extension)
    {
        if ($origin->isDir()) {
            throw new RuntimeException('The file cannot be a directory');
        }

        $this->origin = $origin;
        $this->extension = trim($extension, '.');
    }

    final public static function shouldResize(SplFileInfo $file): bool
    {
        return self::getSize($file, 'width') > static::width()
            || self::getSize($file, 'height') > static::height();
    }

    final public static function createFromUploadedFile(UploadedFile $file): static
    {
        return static::create($file, (string) $file->guessExtension());
    }

    final public static function create(SplFileInfo $file, string $extension): static
    {
        return new static($file, $extension);
    }

    final public function run(): SplFileInfo
    {
        $data = File::getContent($this->getOrigin()->getPathname());

        $originGd = File::createImageFromString($data);

        $cropGd = File::imageCrop($originGd, $this->getRectangleForCrop());

        $resizeGd = File::imageScale($cropGd, static::width(), static::height());

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

    /**
     * @return array{x: int, y: int, width: int, height: int}
     */
    abstract protected function getRectangleForCrop(): array;

    final protected static function getSize(SplFileInfo $file, string $key): int
    {
        $imageSize = File::getImageSize($file->getPathname());

        return match ($key) {
            'width' => (int) $imageSize[0],
            'height' => (int) $imageSize[1],
            default => throw new DomainException(
                sprintf('Key %s is not supported. Available options %s.', $key, implode(',', ['width', 'height']))
            )
        };
    }

    private function createTemporaryFile(): string
    {
        return File::createTmpFile(self::TMP_PREFIX).'.'.$this->extension;
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
