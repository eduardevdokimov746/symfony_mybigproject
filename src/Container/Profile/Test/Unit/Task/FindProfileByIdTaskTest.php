<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Data\Repository\ProfileRepository;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Exception\ProfileNotFoundException;
use App\Container\Profile\Task\FindProfileByIdTask;
use App\Ship\Parent\Test\TestCase;

class FindProfileByIdTaskTest extends TestCase
{
    public function testRun(): void
    {
        $profileRepository = $this->createStub(ProfileRepository::class);
        $profileRepository->method('find')->willReturn(new Profile($this->createUser()));

        $findProfileByIdTask = new FindProfileByIdTask($profileRepository);

        $profile = $findProfileByIdTask->run(1);

        $this->assertInstanceOf(Profile::class, $profile);
    }

    public function testRunExceptException(): void
    {
        $profileRepository = $this->createStub(ProfileRepository::class);

        $findProfileByIdTask = new FindProfileByIdTask($profileRepository);

        $this->expectException(ProfileNotFoundException::class);

        $findProfileByIdTask->run(1);
    }
}
