<?php

declare(strict_types=1);

namespace App\Ship;

use App\Container\User\Data\Repository\Doc\UserRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MainUserLoader implements UserLoaderInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->userRepository->findActiveOneBy(['login' => $identifier]);
    }
}
