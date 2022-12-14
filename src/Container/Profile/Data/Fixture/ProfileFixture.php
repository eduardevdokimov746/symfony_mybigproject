<?php

declare(strict_types=1);

namespace App\Container\Profile\Data\Fixture;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Data\Fixture\UserFixture;
use App\Container\User\Entity\Doc\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProfileFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private CreateProfileTask $createProfileTask
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $defaultUser = $this->getReference(UserFixture::REFERENCE);
        $admin = $this->getReference(UserFixture::REFERENCE_ADMIN);
        $inactiveUser = $this->getReference(UserFixture::INACTIVE);

        /** @var User */
        foreach ([$defaultUser, $admin, $inactiveUser] as $user) {
            $this->createProfileTask->lazy()->run(
                $user,
                static function (Profile $profile): void {
                    $profile
                        ->setFirstName('FirstName')
                        ->setLastName('LastName')
                        ->setPatronymic('Patronymic')
                        ->setAbout('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt'.
                            'ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation '.
                            'ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in'.
                            'reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur'.
                            'sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id'.
                            'est laborum.')
                    ;
                }
            );
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixture::class];
    }
}
