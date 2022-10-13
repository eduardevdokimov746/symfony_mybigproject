<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Data\Repository\ProfileRepository;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Exception\ProfileNotFoundException;
use App\Container\Profile\Task\FindProfileByIdTask;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindProfileByIdTaskTest extends KernelTestCase
{
    private FindProfileByIdTask $findProfileByIdTask;

    protected function setUp(): void
    {
        $profileRepository = self::getContainer()->get(ProfileRepository::class);
        $this->findProfileByIdTask = new FindProfileByIdTask($profileRepository);
    }

    public function testRun(): void
    {
        $profile = $this->findProfileByIdTask->run(1);

        $this->assertInstanceOf(Profile::class, $profile);
    }

    public function testRunExceptException(): void
    {
        $this->expectException(ProfileNotFoundException::class);

        $this->findProfileByIdTask->run(10000);
    }
}
