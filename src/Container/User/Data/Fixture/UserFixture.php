<?php

namespace App\Container\User\Data\Fixture;

use App\Container\User\Entity\Doc\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public const REFERENCE = 'user';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            'ens',
            'ens@mail.com',
            'ens',
            fn(User $user, $plainPassword) => $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        $manager->persist($user);
        $manager->flush();

        $this->setReference(self::REFERENCE, $user);
    }
}