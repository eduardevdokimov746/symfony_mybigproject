<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Container\Profile\Task\UpdateProfileFullNameAndAboutTask;
use App\Ship\Parent\Test\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProfileFullNameAndAboutTaskTest extends TestCase
{
    public function testRun(): void
    {
        $profile = (new Profile($this->createUser()))
            ->setFirstName('firstName')
            ->setLastName('lastName')
            ->setPatronymic('patronymic')
            ->setAbout('about')
        ;

        $entityManager = self::createStub(EntityManagerInterface::class);
        $findProfileByIdTask = self::createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn($profile);

        $updateProfileFullNameAndAboutTask = new UpdateProfileFullNameAndAboutTask($findProfileByIdTask, $entityManager);

        $updatedProfile = $updateProfileFullNameAndAboutTask->run(
            1,
            'update firstName',
            'update lastName',
            'update patronymic',
            'update about'
        );

        self::assertSame('update firstName', $updatedProfile->getFirstName());
        self::assertSame('update lastName', $updatedProfile->getLastName());
        self::assertSame('update patronymic', $updatedProfile->getPatronymic());
        self::assertSame('update about', $updatedProfile->getAbout());
    }
}
