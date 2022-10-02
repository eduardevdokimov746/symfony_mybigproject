<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Data\Repository;

use App\Container\AdminSection\Category\Entity\Book\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getAllWithPagination(int $firstResult, int $maxResults): Paginator
    {
        $sql = 'SELECT c FROM '.Category::class.' c';

        $query = $this->getEntityManager()
            ->createQuery($sql)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
        ;

        $p = new Paginator($query, false);

        return new Paginator($query, false);
    }
}
