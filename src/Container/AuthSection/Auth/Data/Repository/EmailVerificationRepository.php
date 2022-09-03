<?php

namespace App\Container\AuthSection\Auth\Data\Repository;

use App\Container\AuthSection\Auth\Entity\Doc\EmailVerification;
use App\Ship\Parent\Repository;
use Doctrine\Persistence\ManagerRegistry;

class EmailVerificationRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailVerification::class);
    }
}