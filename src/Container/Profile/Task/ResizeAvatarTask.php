<?php

namespace App\Container\Profile\Task;

use App\Ship\Parent\Task;
use DomainException;
use GdImage;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResizeAvatarTask extends Task
{
    public const AVATAR_HEIGHT = 50;
    public const AVATAR_WIDTH  = 50;
    public const TMP_PREFIX    = 'resize_';

    private function __construct(
        private SplFileInfo $origin,
        private string      $extension
    )
    {
        if ($this->origin->isDir()) throw new RuntimeException('The file cannot be a directory');
    }

    public static function shouldResize(SplFileInfo $file): bool
    {
        return self::getSize($file, 'width') > self::AVATAR_WIDTH || self::getSize($file, 'height') > self::AVATAR_HEIGHT;
    }

    public static function getSize(SplFileInfo $file, string $key): int
    {
        return match ($key) {
            'width' => getimagesize($file->getPathname())[0],
            'height' => getimagesize($file->getPathname())[1],
            default => throw new DomainException(
                sprintf('Key %s is not supported. Available options %s.', $key, implode(',', ['width', 'height']))
            )
        };
    }

    public static function createFromUploadedFile(UploadedFile $file): self
    {
        return self::create($file, $file->guessExtension());
    }

    public static function create(SplFileInfo $file, string $extension): self
    {
        return new self($file, $extension);
    }

    public function run(): SplFileInfo
    {
        $originGd = imagecreatefromstring(file_get_contents($this->origin->getPathname()));

        $cropGd = $this->crop($originGd);

        $resizeGd = imagescale(
            $cropGd,
            self::AVATAR_WIDTH,
            self::AVATAR_HEIGHT,
            IMG_BICUBIC
        );

        $tmpFile = $this->createTemporaryFile();

        $this->write($tmpFile, $resizeGd);

        return new SplFileInfo($tmpFile);
    }

    private function crop(GdImage $originGd): GdImage
    {
        return imagecrop($originGd, $this->calcCropSizeAndOffset());
    }

    private function calcCropSizeAndOffset(): array
    {
        $originWidth = self::getSize($this->origin, 'width');
        $originHeight = self::getSize($this->origin, 'height');

        $cropSize = min($originWidth, $originHeight);

        if ($originHeight === $cropSize)
            $x = round(($originWidth - $cropSize) / 2);
        else
            $y = round(($originHeight - $cropSize) / 2);

        return [
            'x'      => $x ?? 0,
            'y'      => $y ?? 0,
            'width'  => $cropSize,
            'height' => $cropSize
        ];
    }

    private function createTemporaryFile(): string
    {
        $tmpFileName = tempnam(sys_get_temp_dir(), self::TMP_PREFIX) . '.' . ltrim($this->extension, '.');

        return $tmpFileName;
    }

    private function write(string $tmpFile, GdImage $resize): void
    {
        switch ($this->extension) {
            case ('png'):
                imagepng($resize, $tmpFile, quality: 0);
                break;
            case ('jpg'):
                imagejpeg($resize, $tmpFile, quality: 100);
                break;
            case ('gif'):
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