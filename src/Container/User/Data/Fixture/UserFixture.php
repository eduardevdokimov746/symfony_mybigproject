<?php

declare(strict_types=1);

namespace App\Container\User\Data\Fixture;

use App\Container\User\Task\CreateUserTask;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const REFERENCE = 'user';

    public function __construct(
        private CreateUserTask $createUserTask
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->createUserTask->run(
            'ens',
            'ens@mail.com',
            'ens'
        );

        $this->setReference(self::REFERENCE, $user);
    }
}
