<?php

declare(strict_types=1);

namespace App\Container\Profile\Task;

use App\Ship\Parent\Task;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use App\Ship\Service\ImageStorage\ImageStorage;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarTask extends Task
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeleteAvatarTask $deleteAvatarTask,
        private FindProfileByIdTask $findProfileById,
        private ImageStorage $imageStorage
    ) {
    }

    public function run(int $profileId, UploadedFile $file): string|false
    {
        $profile = $this->findProfileById->run($profileId);

        $this->entityManager->beginTransaction();

        try {
            $this->deleteAvatarTask->run($profileId);

            if (AvatarImageResizeService::shouldResize($file)) {
                $file = AvatarImageResizeService::createFromUploadedFile($file)->run();
            }

            $fileName = $this->imageStorage->store($file, ImageStorageEnum::Avatar);

            $profile->setAvatar($fileName);

            $this->entityManager->commit();
        } catch (RuntimeException) {
            $this->entityManager->rollback();

            return false;
        }

        return $fileName;
    }
}
