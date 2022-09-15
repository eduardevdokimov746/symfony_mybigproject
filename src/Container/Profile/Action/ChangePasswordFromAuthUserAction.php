<?php

declare(strict_types=1);

namespace App\Container\Profile\Action;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Ship\Parent\Action;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChangePasswordFromAuthUserAction extends Action
{
    public function __construct(
        private ChangeUserPasswordTask $changeUserPasswordTask,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function run(string $plainPassword): User
    {
        if (null === $user = $this->tokenStorage->getToken()?->getUser()) {
            throw new RuntimeException('User is not authenticated');
        }

        return $this->changeUserPasswordTask->run($user->getId(), $plainPassword);
    }
}
