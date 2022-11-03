<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Task;

use App\Container\AdminSection\Category\Data\Repository\CategoryRepository;
use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Container\AdminSection\Category\Exception\CategoryNotFoundException;
use App\Ship\Parent\Task;

class FindCategoryByIdTask extends Task
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function run(int $id): Category
    {
        $category = $this->categoryRepository->find($id);

        if (null === $category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }
}
