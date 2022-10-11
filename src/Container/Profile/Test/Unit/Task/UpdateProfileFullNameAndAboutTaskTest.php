<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Container\Profile\Task\UpdateProfileFullNameAndAboutTask;
use App\Container\User\Test\Trait\CreateUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UpdateProfileFullNameAndAboutTaskTest extends TestCase
{
    use CreateUserTrait;

    public function testRun(): void
    {
        $profile = (new Profile($this->createUser()))
            ->setFirstName('firstName')
            ->setLastName('lastName')
            ->setPatronymic('patronymic')
            ->setAbout('about')
        ;

        $entityManager = $this->createStub(EntityManagerInterface::class);
        $findProfileByIdTask = $this->createStub(FindProfileByIdTask::class);
        $findProfileByIdTask->method('run')->willReturn($profile);

        $updateProfileFullNameAndAboutTask = new UpdateProfileFullNameAndAboutTask($findProfileByIdTask, $entityManager);

        $updatedProfile = $updateProfileFullNameAndAboutTask->run(
            1,
            'update firstName',
            'update lastName',
            'update patronymic',
            'update about'
        );

        $this->assertSame('update firstName', $updatedProfile->getFirstName());
        $this->assertSame('update lastName', $updatedProfile->getLastName());
        $this->assertSame('update patronymic', $updatedProfile->getPatronymic());
        $this->assertSame('update about', $updatedProfile->getAbout());
    }
}
