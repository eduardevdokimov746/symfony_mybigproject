<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Unit\Task;

use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Test\Trait\CreateUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateProfileTaskTest extends TestCase
{
    use CreateUserTrait;

    public function testRun(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);

        $createProfileTask = new CreateProfileTask($entityManager);

        $profile = $createProfileTask->run($this->createUser(), 'firstName', 'lastName', 'patronymic');

        $this->assertSame('firstName', $profile->getFirstName());
        $this->assertSame('lastName', $profile->getLastName());
        $this->assertSame('patronymic', $profile->getPatronymic());
    }
}
