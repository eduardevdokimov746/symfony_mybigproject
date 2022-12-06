<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Data\Repository;

use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Parent\Repository;
use App\Ship\Trait\GetAllWithPaginationTrait;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends Repository<Category>
 */
class CategoryRepository extends Repository
{
    /** @use GetAllWithPaginationTrait<Category> */
    use GetAllWithPaginationTrait;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Category::class);

        $this->setPaginator($paginator);
    }
}
