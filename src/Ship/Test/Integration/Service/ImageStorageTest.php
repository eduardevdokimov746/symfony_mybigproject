<?php

declare(strict_types=1);

namespace App\Ship\Test\Integration\Service;

use App\Ship\Contract\ImageResize;
use App\Ship\Helper\File;
use App\Ship\Parent\Test\KernelTestCase;
use App\Ship\Service\ImageStorage\ImageStorage;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use SplFileInfo;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Filesystem\Filesystem;

class ImageStorageTest extends KernelTestCase
{
    private ImageStorage $imageStorage;
    private Packages $packages;
    private string $publicDir;
    private SplFileInfo $file;

    protected function setUp(): void
    {
        $filesystem = self::getContainer()->get(Filesystem::class);
        $this->packages = self::getContainer()->get(Packages::class);
        $this->publicDir = self::getContainer()->getParameter('app_public_dir');

        $this->imageStorage = new ImageStorage($filesystem, $this->packages, $this->publicDir);
        $this->file = new SplFileInfo(File::createTmpImage(ImageResize::TMP_PREFIX, 50, 50));
    }

    /**
     * @dataProvider imageStorageProvider
     */
    public function testStore(ImageStorageEnum $enum): void
    {
        $storeFile = $this->imageStorage->store($this->file, $enum);

        self::assertFileExists($this->publicDir.$this->packages->getPackage($enum->value)->getUrl($storeFile));

        unlink($this->publicDir.$this->packages->getPackage($enum->value)->getUrl($storeFile));
    }

    /**
     * @dataProvider imageStorageProvider
     */
    public function testRemove(ImageStorageEnum $enum): void
    {
        $storeFile = $this->imageStorage->store($this->file, $enum);

        $this->imageStorage->remove($storeFile, $enum);

        self::assertFileDoesNotExist($this->publicDir.$this->packages->getPackage($enum->value)->getUrl($storeFile));
    }

    /**
     * @return list<list<ImageStorageEnum>>
     */
    public function imageStorageProvider(): array
    {
        return [ImageStorageEnum::cases()];
    }
}
