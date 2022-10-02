<?php

namespace App\Container\AdminSection\Category\Task;

use App\Container\AdminSection\Category\Data\Repository\CategoryRepository;
use App\Ship\Parent\Task;
use Doctrine\ORM\Tools\Pagination\Paginator;

class GetAllCategoriesWithPaginationTask extends Task
{
    public const MAX_RESULTS = 2;

    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function run(int $page): array
    {

//        $firstResult = --$page * self::MAX_RESULTS;

        $paginator = $this->categoryRepository->getAllWithPagination(0, self::MAX_RESULTS);



//        return ;
    }
}