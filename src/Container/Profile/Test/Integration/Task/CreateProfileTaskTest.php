<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Entity\Doc\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateProfileTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $createProfileTask = new CreateProfileTask($entityManager);

        $user = new User('user', 'user@mail.com', 'password', fn () => 'hash');

        $entityManager->persist($user);
        $entityManager->flush();

        $profile = $createProfileTask->run($user, 'firstName', 'lastName', 'patronymic');

        $this->assertSame('firstName', $profile->getFirstName());
        $this->assertSame('lastName', $profile->getLastName());
        $this->assertSame('patronymic', $profile->getPatronymic());
        $this->assertEquals($profile, $user->getProfile());
    }
}
