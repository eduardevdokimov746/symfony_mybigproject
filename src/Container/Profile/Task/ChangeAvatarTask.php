<?php

namespace App\Container\Profile\Task;

use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarTask extends Task
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeleteAvatarTask       $deleteAvatarTask,
        private Filesystem             $filesystem,
        private FindProfileById        $findProfileById,
        private Packages               $asset
    )
    {
    }

    public function run(int $profileId, UploadedFile $file): string|false
    {
        $profile = $this->findProfileById->run($profileId);

        $this->entityManager->beginTransaction();

        try {
            $this->deleteAvatarTask->run($profileId);

            if (ResizeAvatarTask::shouldResize($file))
                $file = ResizeAvatarTask::createFromUploadedFile($file)->run();

            $fileName = $this->storeFile($file);

            $profile->setAvatar($fileName);

            $this->entityManager->commit();
        } catch (RuntimeException) {
            $this->entityManager->rollback();
            return false;
        }

        return $fileName;
    }

    private function storeFile(SplFileInfo $file): string
    {
        $fileName = $this->makeFileName($file);
        $replacePath = ltrim($this->asset->getUrl($fileName, 'avatar'), '/');

        if (is_uploaded_file($file->getPathname()))
            move_uploaded_file($file->getPathname(), $replacePath);
        else
            $this->filesystem->rename($file->getPathname(), $replacePath);

        return $fileName;
    }

    private function makeFileName(SplFileInfo $file): string
    {
        $extension = $file instanceof UploadedFile ? $file->guessExtension() : $file->getExtension();

        return uniqid() . (!$extension ?: ".$extension");
    }
}