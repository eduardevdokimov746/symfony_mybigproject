<?php

declare(strict_types=1);

namespace App\Container\User\Trait;

use App\Container\User\Entity\Doc\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait UserPasswordHasherTrait
{
    private function userPasswordHasher(UserPasswordHasherInterface $userPasswordHasher): callable
    {
        return static function (User $user, string $plainPassword) use ($userPasswordHasher): string {
            return $userPasswordHasher->hashPassword($user, $plainPassword);
        };
    }
}
