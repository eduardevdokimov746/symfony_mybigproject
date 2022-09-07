<?php

namespace App\Container\User\Task;

use App\Container\User\Entity\Doc\User;
use App\Container\User\Trait\UserPasswordHasherTrait;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTask extends Task
{
    use UserPasswordHasherTrait;

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
            $this->userPasswordHasher($this->passwordHasher)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}