<?php

declare(strict_types=1);

namespace App\Container\Profile\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Ship\Parent\Task;
use App\Ship\Service\ImageStorage\ImageStorage;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use RuntimeException;
use SplFileInfo;

class ChangeAvatarTask extends Task
{
    public function __construct(
        private ImageStorage $imageStorage
    ) {
    }

    public function run(Profile $profile, ?SplFileInfo $file): string
    {
        $this->beginTransaction();

        $avatar = $profile->getAvatar();

        try {
            if (!$profile->isDefaultAvatar()) {
                $this->imageStorage->remove($profile->getAvatar(), ImageStorageEnum::Avatar);

                $avatar = null;
            }

            if (null !== $file) {
                $avatar = $this->imageStorage->store($file, ImageStorageEnum::Avatar);
            }

            $profile->setAvatar($avatar);

            $this->commit();
        } catch (RuntimeException) {
            $this->rollback();
        }

        return $profile->getAvatar();
    }
}
