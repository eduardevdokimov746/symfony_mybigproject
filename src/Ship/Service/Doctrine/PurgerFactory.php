<?php

declare(strict_types=1);

namespace App\Ship\Service\Doctrine;

use Doctrine\Bundle\FixturesBundle\Purger\PurgerFactory as PurgerFactoryInterface;
use Doctrine\Common\DataFixtures\Purger\PurgerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @codeCoverageIgnore
 */
class PurgerFactory implements PurgerFactoryInterface
{
    public function createForEntityManager(?string $emName, EntityManagerInterface $em, array $excluded = [], bool $purgeWithTruncate = false): PurgerInterface
    {
        return new PostgresPurger();
    }
}
