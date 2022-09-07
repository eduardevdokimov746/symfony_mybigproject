<?php

namespace App\Container\User\Trait;

use App\Container\User\Entity\Doc\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait UserPasswordHasherTrait
{
    private function userPasswordHasher(UserPasswordHasherInterface $userPasswordHasher): callable
    {
        return function (User $user, string $plainPassword) use ($userPasswordHasher): string {
            return $this->passwordHasher->hashPassword($user, $plainPassword);
        };
    }
}