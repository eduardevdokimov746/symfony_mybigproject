<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Container\User\Test\Trait\CreateUserTrait;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarTaskTest extends TestCase
{
    use CreateUserTrait;

    public function testRun(): void
    {
        $user = $this->createUser();
        $tmpFile = $this->createTmpFile();

        $entityManager = $this->createStub(EntityManagerInterface::class);
        $findProfileByIdTask = $this->createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn(new Profile($user));
        $deleteAvatarTask = $this->createStub(DeleteAvatarTask::class);
        $imageStorageService = $this->createStub(ImageStorage::class);
        $imageStorageService->method('store')->willReturn('avatar.png');
        $uploadedFile = $this->createStub(UploadedFile::class);
        $uploadedFile->method('getPathname')->willReturn($tmpFile);

        $changeAvatarTask = new ChangeAvatarTask($entityManager, $deleteAvatarTask, $findProfileByIdTask, $imageStorageService);

        $avatar = $changeAvatarTask->run(1, $uploadedFile);

        $this->assertSame('avatar.png', $avatar);
        $this->assertSame($avatar, $user->getProfile()->getAvatar());
    }

    private function createTmpFile(): string
    {
        $tmpFile = tempnam(sys_get_temp_dir(), AvatarImageResizeService::TMP_PREFIX);

        $content = imagecreatetruecolor(AvatarImageResizeService::WIDTH, AvatarImageResizeService::HEIGHT);
        imagepng($content, $tmpFile);
        imagedestroy($content);

        return $tmpFile;
    }
}
