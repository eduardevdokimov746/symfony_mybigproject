<?php

declare(strict_types=1);

namespace App\Container\User\Task;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Ship\Parent\Task;

class FindUserByIdTask extends Task
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function run(int $id, bool $withDisabled = false): User
    {
        if ($withDisabled) {
            $user = $this->userRepository->findOneBy(['id' => $id]);
        } else {
            $user = $this->userRepository->findActiveOneBy(['id' => $id]);
        }

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
