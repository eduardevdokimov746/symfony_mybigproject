<?php

declare(strict_types=1);

namespace App\Container\Profile\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProfileFullNameAndAboutTask extends Task
{
    public function __construct(
        private FindProfileById        $findProfileById,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function run(
        int     $id,
        ?string $firstName,
        ?string $lastName,
        ?string $patronymic,
        ?string $about,
    ): Profile
    {
        $profile = $this->findProfileById->run($id);

        $profile
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPatronymic($patronymic)
            ->setAbout($about);

        $this->entityManager->flush();

        return $profile;
    }
}