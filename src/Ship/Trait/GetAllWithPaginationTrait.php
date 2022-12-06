<?php

declare(strict_types=1);

namespace App\Ship\Trait;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @template T
 */
trait GetAllWithPaginationTrait
{
    protected PaginatorInterface $paginator;

    public function setPaginator(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
    }

    /**
     * @return PaginationInterface<int, T>
     */
    public function getAllWithPagination(int $page, int $limit): PaginationInterface
    {
        $dql = 'SELECT c FROM '.$this->_entityName.' as c ORDER BY c.id';

        $query = $this->getEntityManager()->createQuery($dql);

        return $this->paginator->paginate($query, $page, $limit);
    }
}
