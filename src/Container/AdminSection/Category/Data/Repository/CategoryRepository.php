<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Data\Repository;

use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Parent\Repository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends Repository<Category>
 */
class CategoryRepository extends Repository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Category::class);

        $this->paginator = $paginator;
    }

    /**
     * @return PaginationInterface<int, Category>
     */
    public function getAllWithPagination(int $page, int $limit): PaginationInterface
    {
        $dql = 'SELECT c FROM '.Category::class.' as c ORDER BY c.id';

        $query = $this->getEntityManager()->createQuery($dql);

        return $this->paginator->paginate($query, $page, $limit);
    }
}
