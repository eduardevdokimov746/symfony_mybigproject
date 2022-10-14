<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Ship\Contract\ImageResize;
use App\Ship\Parent\Test\KernelTestCase;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarTaskTest extends KernelTestCase
{
    private string $tmpFile;

    protected function tearDown(): void
    {
        unlink($this->tmpFile);
    }

    public function testRun(): void
    {
        $this->tmpFile = $this->createTmpFile();

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $deleteAvatarTask = self::getContainer()->get(DeleteAvatarTask::class);
        $findProfileByIdTask = self::getContainer()->get(FindProfileByIdTask::class);
        $imageStorageService = $this->createStub(ImageStorage::class);
        $uploadedFile = $this->createStub(UploadedFile::class);
        $uploadedFile->method('getPathname')->willReturn($this->tmpFile);

        $changeAvatarTask = new ChangeAvatarTask($entityManager, $deleteAvatarTask, $findProfileByIdTask, $imageStorageService);

        $profile = $findProfileByIdTask->run(1);
        $oldAvatar = $profile->getAvatar();

        $avatar = $changeAvatarTask->run(1, $uploadedFile);

        $this->assertNotSame($oldAvatar, $avatar);
    }

    private function createTmpFile(): string
    {
        $tmpFile = tempnam(sys_get_temp_dir(), ImageResize::TMP_PREFIX);

        $content = imagecreatetruecolor(AvatarImageResizeService::WIDTH, AvatarImageResizeService::HEIGHT);
        imagepng($content, $tmpFile);
        imagedestroy($content);

        return $tmpFile;
    }
}
