<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Integration\Task;

use App\Container\AdminSection\Category\Data\Repository\CategoryRepository;
use App\Container\AdminSection\Category\Exception\CategoryNotFoundException;
use App\Container\AdminSection\Category\Task\FindCategoryByIdTask;
use App\Ship\Parent\Test\KernelTestCase;

class FindCategoryByIdTaskTest extends KernelTestCase
{
    private FindCategoryByIdTask $findCategoryByIdTask;

    protected function setUp(): void
    {
        $categoryRepository = self::getContainer()->get(CategoryRepository::class);
        $this->findCategoryByIdTask = new FindCategoryByIdTask($categoryRepository);
    }

    public function testRun(): void
    {
        $category = $this->findCategoryByIdTask->run(1);

        self::assertSame(1, $category->getId());
    }

    public function testRunNotExists(): void
    {
        $this->expectException(CategoryNotFoundException::class);

        $this->findCategoryByIdTask->run(999);
    }
}
