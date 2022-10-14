<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Ship\Parent\Test\KernelTestCase;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;

class DeleteAvatarTaskTest extends KernelTestCase
{
    private DeleteAvatarTask $deleteAvatarTask;
    private FindProfileByIdTask $findProfileByIdTask;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->findProfileByIdTask = self::getContainer()->get(FindProfileByIdTask::class);
        $imageStorageService = $this->createStub(ImageStorage::class);

        $this->deleteAvatarTask = new DeleteAvatarTask($this->entityManager, $this->findProfileByIdTask, $imageStorageService);
    }

    public function testRunExpectFalse(): void
    {
        $this->assertFalse($this->deleteAvatarTask->run(1));
    }

    public function testRunExpectTrue(): void
    {
        $profile = $this->findProfileByIdTask->run(1);
        $profile->setAvatar('avatar.png');
        $this->entityManager->flush();

        $this->assertTrue($this->deleteAvatarTask->run(1));
        $this->assertSame(Profile::DEFAULT_AVATAR, $profile->getAvatar());
    }
}
