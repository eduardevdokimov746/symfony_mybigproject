<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\ImageResize\Trait;

use App\Ship\Contract\ImageResize;
use App\Ship\Helper\File;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait AbstractImageResizeServiceTestTrait
{
    /**
     * @dataProvider imageSizeProvider
     */
    public function testShouldResizeTrue(int $width, int $height): void
    {
        $file = File::createTmpImage(ImageResize::TMP_PREFIX, $width, $height);
        $splFile = new SplFileInfo($file);

        self::assertTrue($this->imageResizeService::shouldResize($splFile));
    }

    public function testShouldResizeFalse(): void
    {
        $file = File::createTmpImage(ImageResize::TMP_PREFIX, $this->imageResizeService::WIDTH, $this->imageResizeService::HEIGHT);
        $splFile = new SplFileInfo($file);

        self::assertFalse($this->imageResizeService::shouldResize($splFile));
    }

    public function testCreateSuccess(): void
    {
        $file = File::createTmpImage(ImageResize::TMP_PREFIX, 100, 100);

        self::assertInstanceOf(
            $this->imageResizeService,
            $this->imageResizeService::create(new SplFileInfo($file), 'png')
        );
    }

    public function testCreateFail(): void
    {
        self::expectExceptionMessage('dir');

        $this->imageResizeService::create(new SplFileInfo(__DIR__), 'png');
    }

    public function testCreateFromUploadedFile(): void
    {
        $uploadedFile = $this->createStub(UploadedFile::class);
        $uploadedFile->method('guessExtension')->willReturn('png');

        self::assertInstanceOf(
            $this->imageResizeService,
            $this->imageResizeService::createFromUploadedFile($uploadedFile)
        );
    }

    /**
     * @dataProvider imageSizeProvider
     */
    public function testRun(int $width, int $height): void
    {
        $file = File::createTmpImage(ImageResize::TMP_PREFIX, $width, $height);

        $splFile = new SplFileInfo($file);
        $avatarResize = $this->imageResizeService::create($splFile, 'png');
        $resultFile = $avatarResize->run();

        [$checkWidth, $checkHeight] = File::getImageSize($resultFile->getPathname());

        self::assertSame($this->imageResizeService::WIDTH, $checkWidth);
        self::assertSame($this->imageResizeService::HEIGHT, $checkHeight);
    }

    /**
     * @return list<list<int>>
     */
    public function imageSizeProvider(): array
    {
        return [
            [1920, 1080],
            [1080, 1920],
        ];
    }
}
