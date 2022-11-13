<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Ship\Contract\ImageResize;
use App\Ship\Helper\File;
use App\Ship\Parent\Test\KernelTestCase;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $file = File::createTmpImage(ImageResize::TMP_PREFIX, AvatarImageResizeService::WIDTH, AvatarImageResizeService::HEIGHT);

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $deleteAvatarTask = self::getContainer()->get(DeleteAvatarTask::class);
        $findProfileByIdTask = self::getContainer()->get(FindProfileByIdTask::class);
        $imageStorageService = self::createStub(ImageStorage::class);
        $uploadedFile = self::createStub(UploadedFile::class);
        $uploadedFile->method('getPathname')->willReturn($file);

        $changeAvatarTask = new ChangeAvatarTask($entityManager, $deleteAvatarTask, $findProfileByIdTask, $imageStorageService);

        $profile = $findProfileByIdTask->run(1);
        $oldAvatar = $profile->getAvatar();

        $avatar = $changeAvatarTask->run(1, $uploadedFile);

        self::assertNotSame($oldAvatar, $avatar);
    }
}
