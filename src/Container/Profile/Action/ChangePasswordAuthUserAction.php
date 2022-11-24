<?php

declare(strict_types=1);

namespace App\Container\Profile\Action;

use App\Container\Profile\Data\DTO\ChangePasswordAuthUserDTO;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Ship\Helper\Security;
use App\Ship\Parent\Action;

class ChangePasswordAuthUserAction extends Action
{
    public function __construct(
        private ChangeUserPasswordTask $changeUserPasswordTask,
        private Security $security
    ) {
    }

    public function run(ChangePasswordAuthUserDTO $dto): User
    {
        $this->security->checkAuth();

        return $this->changeUserPasswordTask->run($this->security->getUser(), $dto->newPlainPassword);
    }
}
