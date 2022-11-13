<?php

declare(strict_types=1);

namespace App\Ship\Parent;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @template T of object
 *
 * @extends ServiceEntityRepository<T>
 */
class Repository extends ServiceEntityRepository
{
}
