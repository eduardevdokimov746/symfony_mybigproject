<?php

namespace App\Container\User\Data\Repository\Doc;

use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Repository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}