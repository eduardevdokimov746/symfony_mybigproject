<?php

namespace App\Container\User\Data\Fixture;

use App\Container\User\Entity\Doc\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setLogin('ens');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'ens'));

        $manager->persist($user);

        $manager->flush();
    }
}