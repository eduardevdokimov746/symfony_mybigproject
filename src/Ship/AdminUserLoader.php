<?php

declare(strict_types=1);

namespace App\Ship;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Enum\RoleEnum;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminUserLoader implements UserLoaderInterface
{
    public function __construct(
        private UserRepository $repository
    ) {
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->repository->findActiveOneBy(['login' => $identifier, 'role' => RoleEnum::Admin]);
    }
}
