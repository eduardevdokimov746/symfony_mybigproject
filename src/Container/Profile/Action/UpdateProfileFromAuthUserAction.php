<?php

declare(strict_types=1);

namespace App\Container\Profile\Action;

use App\Container\Profile\Data\DTO\UpdateProfileFromAuthUserDTO;
use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\UpdateProfileFullNameAndAboutTask;
use App\Ship\Helper\Security;
use App\Ship\Parent\Action;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateProfileFromAuthUserAction extends Action
{
    public function __construct(
        private Security $userAuthenticator,
        private UpdateProfileFullNameAndAboutTask $updateProfileFullNameAndAboutTask,
        private ChangeAvatarTask $changeAvatarTask,
        private DeleteAvatarTask $deleteAvatarTask
    ) {
    }

    public function run(UpdateProfileFromAuthUserDTO $updateProfileDTO): void
    {
        $this->userAuthenticator->checkAuth();

        $user = $this->userAuthenticator->getUser();

        $profileId = $user->getProfile()->getId();

        if ($updateProfileDTO->deleteAvatar) {
            $this->deleteAvatarTask->run($profileId);
        }

        if ($updateProfileDTO->avatar instanceof UploadedFile) {
            $this->changeAvatarTask->run($profileId, $updateProfileDTO->avatar);
        }

        $user->setProfile($this->updateProfileFullNameAndAboutTask->run(
            $user->getProfile()->getId(),
            $updateProfileDTO->firstName,
            $updateProfileDTO->lastName,
            $updateProfileDTO->patronymic,
            $updateProfileDTO->about
        ));
    }
}
