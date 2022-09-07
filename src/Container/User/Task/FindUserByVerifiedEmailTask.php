<?php

namespace App\Container\User\Task;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Exception\UserNotFoundException;
use App\Ship\Parent\Task;

class FindUserByVerifiedEmailTask extends Task
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function run(string $email): User
    {
        $user = $this->userRepository->findOneBy(['email' => $email, 'emailVerified' => true]);

        if (is_null($user))
            throw new UserNotFoundException();

        return $user;
    }
}