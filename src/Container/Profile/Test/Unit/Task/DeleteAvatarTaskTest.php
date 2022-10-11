<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Container\User\Test\Trait\CreateUserTrait;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DeleteAvatarTaskTest extends TestCase
{
    use CreateUserTrait;

    public function testRunExpectFalse(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $findProfileByIdTask = $this->createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn(new Profile($this->createUser()));
        $imageStorageService = $this->createStub(ImageStorage::class);

        $deleteAvatarTaskTest = new DeleteAvatarTask($entityManager, $findProfileByIdTask, $imageStorageService);

        $this->assertFalse($deleteAvatarTaskTest->run(1));
    }

    public function testRunExpectTrue(): void
    {
        $profile = (new Profile($this->createUser()))->setAvatar('avatar.png');

        $entityManager = $this->createStub(EntityManagerInterface::class);
        $findProfileByIdTask = $this->createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn($profile);
        $imageStorageService = $this->createStub(ImageStorage::class);

        $deleteAvatarTaskTest = new DeleteAvatarTask($entityManager, $findProfileByIdTask, $imageStorageService);

        $this->assertTrue($deleteAvatarTaskTest->run(1));
        $this->assertSame(Profile::DEFAULT_AVATAR, $profile->getAvatar());
    }
}
