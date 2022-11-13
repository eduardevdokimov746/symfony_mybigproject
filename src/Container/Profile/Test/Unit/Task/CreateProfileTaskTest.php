<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Task\CreateProfileTask;
use App\Ship\Parent\Test\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class CreateProfileTaskTest extends TestCase
{
    public function testRun(): void
    {
        $entityManager = self::createStub(EntityManagerInterface::class);

        $createProfileTask = new CreateProfileTask($entityManager);

        $profile = $createProfileTask->run($this->createUser(), 'firstName', 'lastName', 'patronymic');

        self::assertSame('firstName', $profile->getFirstName());
        self::assertSame('lastName', $profile->getLastName());
        self::assertSame('patronymic', $profile->getPatronymic());
    }
}
