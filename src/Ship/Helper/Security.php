<?php

declare(strict_types=1);

namespace App\Ship\Helper;

use App\Container\User\Entity\Doc\User;
use App\Ship\Exception\UserNotAuthException;
use Symfony\Component\Security\Core\Security as SymfonySecurity;
use Symfony\Component\Security\Core\User\UserInterface;

class Security
{
    public function __construct(
        private SymfonySecurity $baseSecurity
    ) {
    }

    public function getUser(): ?User
    {
        if ($this->isAuth()) {
            /** @var User */
            return $this->baseSecurity->getUser();
        }

        return null;
    }

    /** @phpstan-assert-if-true User $this->getUser() */
    public function isAuth(): bool
    {
        return $this->baseSecurity->getUser() instanceof UserInterface;
    }

    /** @phpstan-assert User $this->getUser() */
    public function checkAuth(): void
    {
        if (!$this->isAuth()) {
            throw new UserNotAuthException();
        }
    }
}
