<?php

namespace App\Container\User\Data\Repository\Doc;

use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Repository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends Repository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->findActiveOneBy(['login' => $identifier]);
    }

    public function findActiveOneBy(array $criteria): ?User
    {
        return $this->findOneBy(array_merge($criteria, ['active' => true]));
    }

    public function existsBy(array $criteria): bool
    {
        return $this->findOneBy($criteria) !== null;
    }
}