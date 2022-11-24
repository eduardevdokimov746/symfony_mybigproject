<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\ChangeAvatarTask;
use App\Ship\Contract\ImageResize;
use App\Ship\Helper\File;
use App\Ship\Parent\Test\TestCase;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarTaskTest extends TestCase
{
    public function testRun(): void
    {
        $user = self::createUser();
        $tmpFile = File::createTmpImage(ImageResize::TMP_PREFIX, AvatarImageResizeService::WIDTH, AvatarImageResizeService::HEIGHT);

        $entityManager = self::createStub(EntityManagerInterface::class);
        $imageStorageService = self::createStub(ImageStorage::class);
        $imageStorageService->method('store')->willReturn('avatar.png');
        $uploadedFile = self::createStub(UploadedFile::class);
        $uploadedFile->method('getPathname')->willReturn($tmpFile);

        $changeAvatarTask = new ChangeAvatarTask($imageStorageService);
        $changeAvatarTask->setEntityManager($entityManager);

        $avatar = $changeAvatarTask->run(new Profile($user), $uploadedFile);

        self::assertSame('avatar.png', $avatar);
        self::assertSame($avatar, $user->getProfile()->getAvatar());
    }
}
