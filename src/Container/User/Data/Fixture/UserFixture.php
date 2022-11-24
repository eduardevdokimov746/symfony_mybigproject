<?php

declare(strict_types=1);

namespace App\Container\User\Data\Fixture;

use App\Container\User\Action\CreateAdminAction;
use App\Container\User\Action\CreateUserAction;
use App\Container\User\Data\DTO\CreateAdminDTO;
use App\Container\User\Data\DTO\CreateUserDTO;
use App\Container\User\Entity\Doc\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const REFERENCE = 'user';
    public const REFERENCE_ADMIN = 'admin';
    public const INACTIVE = 'inactive';

    public function __construct(
        private CreateUserAction $createUserAction,
        private CreateAdminAction $createAdminAction
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->createUserAction->lazy()->run(CreateUserDTO::create([
            'login' => 'ens',
            'email' => 'ens@mail.com',
            'plainPassword' => 'ens',
        ]));

        $admin = $this->createAdminAction->lazy()->run(
            CreateAdminDTO::create([
                'login' => 'admin',
                'email' => 'admin@mail.com',
                'plainPassword' => 'admin',
            ]),
            static fn (User $user) => $user->setEmailVerified(true)
        );

        $inactiveUser = $this->createUserAction->lazy()->run(
            CreateUserDTO::create([
                'login' => 'dis',
                'email' => 'dis@mail.com',
                'plainPassword' => 'dis',
            ]),
            static fn (User $user) => $user->disable()
        );

        $manager->flush();

        $this->setReference(self::REFERENCE, $user);
        $this->setReference(self::REFERENCE_ADMIN, $admin);
        $this->setReference(self::INACTIVE, $inactiveUser);
    }
}
