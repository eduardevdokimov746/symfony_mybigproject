<?php

declare(strict_types=1);

namespace App\Container\Profile\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Ship\Parent\Task;
use App\Ship\Service\ImageStorage\ImageStorage;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class DeleteAvatarTask extends Task
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FindProfileByIdTask $findProfileById,
        private ImageStorage $imageStorage
    ) {
    }

    public function run(int $profileId): bool
    {
        $profile = $this->findProfileById->run($profileId);

        if (Profile::DEFAULT_AVATAR === $profile->getAvatar()) {
            return false;
        }

        $this->entityManager->beginTransaction();

        try {
            $this->imageStorage->remove($profile->getAvatar(), ImageStorageEnum::Avatar);

            $profile->setAvatar(null);

            $this->entityManager->commit();
        } catch (RuntimeException) {
            $this->entityManager->rollback();

            return false;
        }

        return true;
    }
}
