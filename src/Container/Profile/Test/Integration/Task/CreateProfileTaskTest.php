<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Task;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\CreateProfileTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class CreateProfileTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $createProfileTask = new CreateProfileTask();
        $createProfileTask->setEntityManager($entityManager);

        $user = self::createUser();

        $entityManager->persist($user);
        $entityManager->flush();

        $profile = $createProfileTask->run(
            $user,
            static function (Profile $profile): void {
                $profile
                    ->setFirstName('firstName')
                    ->setLastName('lastName')
                    ->setPatronymic('patronymic')
                ;
            }
        );

        self::assertSame('firstName', $profile->getFirstName());
        self::assertSame('lastName', $profile->getLastName());
        self::assertSame('patronymic', $profile->getPatronymic());
        self::assertEquals($profile, $user->getProfile());
    }
}
