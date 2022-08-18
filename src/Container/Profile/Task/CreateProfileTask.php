<?php

namespace App\Container\Profile\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;

class CreateProfileTask extends Task
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function run(User $user, string $firstName = null, string $lastName = null, string $patronymic = null): Profile
    {
        $profile = new Profile($user);
        $profile
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPatronymic($patronymic);

        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        return $profile;
    }
}