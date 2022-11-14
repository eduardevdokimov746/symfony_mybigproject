<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Action;

use App\Container\AdminSection\Category\Data\Repository\CategoryRepository;
use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Parent\Action;
use App\Ship\Trait\GetPageForPaginatorTrait;
use Knp\Component\Pager\Pagination\PaginationInterface;

class GetAllCategoriesWithPaginationAction extends Action
{
    use GetPageForPaginatorTrait;

    public const MAX_RESULTS = 4;

    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    /**
     * @return PaginationInterface<int, Category>
     */
    public function run(string|int $page = null): PaginationInterface
    {
        $page = $this->getPage($page);

        return $this->categoryRepository->getAllWithPagination($page, self::MAX_RESULTS);
    }
}
