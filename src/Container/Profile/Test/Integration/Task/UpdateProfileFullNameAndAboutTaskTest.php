<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Task\FindProfileByIdTask;
use App\Container\Profile\Task\UpdateProfileFullNameAndAboutTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProfileFullNameAndAboutTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $findProfileByIdTask = self::getContainer()->get(FindProfileByIdTask::class);

        $profile = $findProfileByIdTask->run(1);

        $oldFirstName = $profile->getFirstName();
        $oldLastName = $profile->getLastName();
        $oldPatronymic = $profile->getPatronymic();
        $oldAbout = $profile->getAbout();

        $updateProfileFullNameAndAboutTask = new UpdateProfileFullNameAndAboutTask($findProfileByIdTask, $entityManager);

        $updatedProfile = $updateProfileFullNameAndAboutTask->run(
            1,
            'update firstName',
            'update lastName',
            'update patronymic',
            'update about'
        );

        self::assertNotSame($oldFirstName, $updatedProfile->getFirstName());
        self::assertNotSame($oldLastName, $updatedProfile->getLastName());
        self::assertNotSame($oldPatronymic, $updatedProfile->getPatronymic());
        self::assertNotSame($oldAbout, $updatedProfile->getAbout());
    }
}
