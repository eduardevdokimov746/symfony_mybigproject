<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\ImageResize\Trait;

use App\Ship\Contract\ImageResize;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait AbstractImageResizeServiceTestTrait
{
    private SplFileInfo $file;
    private string $tmpFileName;

    protected function setUp(): void
    {
        $this->tmpFileName = tempnam(sys_get_temp_dir(), ImageResize::TMP_PREFIX);
    }

    protected function tearDown(): void
    {
        unlink($this->tmpFileName);
    }

    /**
     * @dataProvider imageSizeProvider
     */
    public function testShouldResizeTrue(int $width, int $height): void
    {
        $this->writeTmpFile($width, $height);

        $splFile = new SplFileInfo($this->tmpFileName);

        $this->assertTrue($this->imageResizeService::shouldResize($splFile));
    }

    public function testShouldResizeFalse(): void
    {
        $this->writeTmpFile($this->imageResizeService::WIDTH, $this->imageResizeService::HEIGHT);

        $splFile = new SplFileInfo($this->tmpFileName);

        $this->assertFalse($this->imageResizeService::shouldResize($splFile));
    }

    public function testCreateSuccess(): void
    {
        $this->assertInstanceOf(
            $this->imageResizeService,
            $this->imageResizeService::create(new SplFileInfo($this->tmpFileName), 'png')
        );
    }

    public function testCreateFail(): void
    {
        $this->expectExceptionMessage('dir');

        $this->imageResizeService::create(new SplFileInfo(__DIR__), 'png');
    }

    public function testCreateFromUploadedFile(): void
    {
        $uploadedFile = $this->createStub(UploadedFile::class);
        $uploadedFile->method('guessExtension')->willReturn('png');

        $this->assertInstanceOf(
            $this->imageResizeService,
            $this->imageResizeService::createFromUploadedFile($uploadedFile)
        );
    }

    /**
     * @dataProvider imageSizeProvider
     */
    public function testRun(int $width, int $height): void
    {
        $this->writeTmpFile($width, $height);

        $splFile = new SplFileInfo($this->tmpFileName);
        $avatarResize = $this->imageResizeService::create($splFile, 'png');
        $resultFile = $avatarResize->run();

        [$checkWidth, $checkHeight] = getimagesize($resultFile->getPathname());

        $this->assertSame($this->imageResizeService::WIDTH, $checkWidth);
        $this->assertSame($this->imageResizeService::HEIGHT, $checkHeight);
    }

    public function imageSizeProvider(): array
    {
        return [
            [1920, 1080],
            [1080, 1920],
        ];
    }

    private function writeTmpFile(int $width, int $height): void
    {
        $content = imagecreatetruecolor($width, $height);
        imagepng($content, $this->tmpFileName);
        imagedestroy($content);
    }
}
