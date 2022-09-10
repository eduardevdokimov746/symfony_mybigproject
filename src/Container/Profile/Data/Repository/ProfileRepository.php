<?php

declare(strict_types=1);

namespace App\Container\Profile\Data\Repository;

use App\Container\Profile\Entity\Doc\Profile;
use App\Ship\Parent\Repository;
use Doctrine\Persistence\ManagerRegistry;

class ProfileRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }
}
