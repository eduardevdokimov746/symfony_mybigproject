<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\UpdateProfileTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProfileTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $profile = self::findUserFromDB()->getProfile();

        $oldFirstName = $profile->getFirstName();
        $oldLastName = $profile->getLastName();
        $oldPatronymic = $profile->getPatronymic();
        $oldAbout = $profile->getAbout();

        $updateProfileTask = new UpdateProfileTask();
        $updateProfileTask->setEntityManager($entityManager);

        $updatedProfile = $updateProfileTask->run(
            $profile,
            static function (Profile $profile): void {
                $profile
                    ->setFirstName('update firstName')
                    ->setLastName('update lastName')
                    ->setPatronymic('update patronymic')
                    ->setAbout('update about')
                ;
            }
        );

        self::assertNotSame($oldFirstName, $updatedProfile->getFirstName());
        self::assertNotSame($oldLastName, $updatedProfile->getLastName());
        self::assertNotSame($oldPatronymic, $updatedProfile->getPatronymic());
        self::assertNotSame($oldAbout, $updatedProfile->getAbout());
    }
}
