<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Container\Profile\Task\UpdateProfileTask;
use App\Ship\Parent\Test\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProfileTaskTest extends TestCase
{
    public function testRun(): void
    {
        $profile = (new Profile(self::createUser()))
            ->setFirstName('firstName')
            ->setLastName('lastName')
            ->setPatronymic('patronymic')
            ->setAbout('about')
        ;

        $entityManager = self::createStub(EntityManagerInterface::class);
        $findProfileByIdTask = self::createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn($profile);

        $updateProfileFullNameAndAboutTask = new UpdateProfileTask();
        $updateProfileFullNameAndAboutTask->setEntityManager($entityManager);

        $updatedProfile = $updateProfileFullNameAndAboutTask->run(
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

        self::assertSame('update firstName', $updatedProfile->getFirstName());
        self::assertSame('update lastName', $updatedProfile->getLastName());
        self::assertSame('update patronymic', $updatedProfile->getPatronymic());
        self::assertSame('update about', $updatedProfile->getAbout());
    }
}
