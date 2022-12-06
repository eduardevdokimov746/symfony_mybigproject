<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\Action;

use App\Container\User\Exception\UserNotFoundException;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Action;
use App\Ship\Service\ImageStorage\ImageStorage;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use Psr\Log\LoggerInterface;

class DeleteUserByIdAction extends Action
{
    public function __construct(
        private FindUserByIdTask $findUserByIdTask,
        private LoggerInterface $logger,
        private ImageStorage $imageStorage
    ) {
    }

    public function run(int $id): bool
    {
        try {
            $user = $this->findUserByIdTask->run($id, true);

            if (!$user->getProfile()->isDefaultAvatar()) {
                $this->imageStorage->remove($user->getProfile()->getAvatar(), ImageStorageEnum::Avatar);
            }

            $this->remove($user);
            $this->flush();

            $this->logger->info('User deleted', ['id' => $id]);

            return true;
        } catch (UserNotFoundException $e) {
            $this->logger->info($e->getMessage());
        }

        return false;
    }
}
