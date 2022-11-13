<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Ship\Parent\Test\TestCase;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;

class DeleteAvatarTaskTest extends TestCase
{
    public function testRunExpectFalse(): void
    {
        $entityManager = self::createStub(EntityManagerInterface::class);
        $findProfileByIdTask = self::createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn(new Profile($this->createUser()));
        $imageStorageService = self::createStub(ImageStorage::class);

        $deleteAvatarTask = new DeleteAvatarTask($entityManager, $findProfileByIdTask, $imageStorageService);

        self::assertFalse($deleteAvatarTask->run(1));
    }

    public function testRunExpectTrue(): void
    {
        $profile = (new Profile($this->createUser()))->setAvatar('avatar.png');

        $entityManager = self::createStub(EntityManagerInterface::class);
        $findProfileByIdTask = self::createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn($profile);
        $imageStorageService = self::createStub(ImageStorage::class);

        $deleteAvatarTask = new DeleteAvatarTask($entityManager, $findProfileByIdTask, $imageStorageService);

        self::assertTrue($deleteAvatarTask->run(1));
        self::assertSame(Profile::DEFAULT_AVATAR, $profile->getAvatar());
    }
}
