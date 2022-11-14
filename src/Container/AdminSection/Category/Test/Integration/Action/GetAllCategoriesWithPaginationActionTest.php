<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Integration\Action;

use App\Container\AdminSection\Category\Action\GetAllCategoriesWithPaginationAction;
use App\Container\AdminSection\Category\Data\Repository\CategoryRepository;
use App\Ship\Parent\Test\KernelTestCase;

class GetAllCategoriesWithPaginationActionTest extends KernelTestCase
{
    private GetAllCategoriesWithPaginationAction $getAllCategoriesWithPaginationAction;

    protected function setUp(): void
    {
        $categoryRepository = self::getContainer()->get(CategoryRepository::class);
        $this->getAllCategoriesWithPaginationAction = new GetAllCategoriesWithPaginationAction($categoryRepository);
    }

    public function testRunEmptyPage(): void
    {
        $result = $this->getAllCategoriesWithPaginationAction->run();

        self::assertCount(GetAllCategoriesWithPaginationAction::MAX_RESULTS, $result);
    }

    public function testRunValidPage(): void
    {
        $result = $this->getAllCategoriesWithPaginationAction->run(1);

        self::assertCount(GetAllCategoriesWithPaginationAction::MAX_RESULTS, $result);
    }

    public function testRunNotExistsPage(): void
    {
        $result = $this->getAllCategoriesWithPaginationAction->run(999);

        self::assertCount(0, $result);
    }

    public function testRunNotValidPage(): void
    {
        $result = $this->getAllCategoriesWithPaginationAction->run('test');

        self::assertCount(GetAllCategoriesWithPaginationAction::MAX_RESULTS, $result);
    }
}
