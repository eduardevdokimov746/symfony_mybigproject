<?php

namespace App\Container\User\Task;

use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTask extends Task
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface      $entityManager
    )
    {
    }

    public function run(string $login, string $email, string $plainPassword): User
    {
        $user = new User(
            $login,
            $email,
            $plainPassword,
            fn(User $user, string $plainPassword) => $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}