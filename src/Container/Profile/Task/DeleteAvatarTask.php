<?php

declare(strict_types=1);

namespace App\Container\Profile\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Filesystem\Filesystem;

class DeleteAvatarTask extends Task
{
    public function __construct(
        private Filesystem $filesystem,
        private EntityManagerInterface $entityManager,
        private FindProfileById $findProfileById,
        private Packages $asset
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
            $this->filesystem->remove(ltrim($this->asset->getUrl($profile->getAvatar(), 'avatar'), '/'));

            $profile->setAvatar(null);

            $this->entityManager->commit();
        } catch (RuntimeException) {
            $this->entityManager->rollback();

            return false;
        }

        return true;
    }
}
