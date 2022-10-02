<?php

declare(strict_types=1);

namespace App\Container\User\Data\Fixture;

use App\Container\User\Task\CreateUserAdminTask;
use App\Container\User\Task\CreateUserTask;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const REFERENCE = 'user';
    public const REFERENCE_ADMIN = 'admin';

    public function __construct(
        private CreateUserTask $createUserTask,
        private CreateUserAdminTask $createUserAdminTask
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->createUserTask->run(
            'ens',
            'ens@mail.com',
            'ens'
        );

        $admin = $this->createUserAdminTask->run(
            'admin',
            'admin@mail.com',
            'admin'
        );

        $this->setReference(self::REFERENCE, $user);
        $this->setReference(self::REFERENCE_ADMIN, $admin);
    }
}
