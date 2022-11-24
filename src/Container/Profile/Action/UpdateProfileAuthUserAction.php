<?php

declare(strict_types=1);

namespace App\Container\Profile\Action;

use App\Container\Profile\Data\DTO\UpdateProfileAuthUserDTO;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\Profile\Task\UpdateProfileTask;
use App\Ship\Helper\Security;
use App\Ship\Parent\Action;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateProfileAuthUserAction extends Action
{
    public function __construct(
        private Security $userAuthenticator,
        private UpdateProfileTask $updateProfileTask,
        private ChangeAvatarTask $changeAvatarTask
    ) {
    }

    public function run(UpdateProfileAuthUserDTO $dto): void
    {
        $this->userAuthenticator->checkAuth();

        $profile = $this->userAuthenticator->getUser()->getProfile();

        $avatar = match (true) {
            $dto->deleteAvatar => null,
            $dto->avatar instanceof UploadedFile => AvatarImageResizeService::createFromUploadedFile($dto->avatar)->run(),
            default => $profile->getAvatar()
        };

        $this->beginTransaction();

        try {
            if ($avatar !== $profile->getAvatar()) {
                /** @phpstan-ignore-next-line */
                $this->changeAvatarTask->run($profile, $avatar);
            }

            $this->updateProfileTask->run(
                $profile,
                static function (Profile $profile) use ($dto): void {
                    $profile
                        ->setFirstName($dto->firstName)
                        ->setLastName($dto->lastName)
                        ->setPatronymic($dto->patronymic)
                        ->setAbout($dto->about)
                    ;
                }
            );

            $this->commit();
        } catch (RuntimeException) {
            $this->rollback();
        }
    }
}
