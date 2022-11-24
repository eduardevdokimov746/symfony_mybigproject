<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Integration\Action;

use App\Container\AdminSection\Category\Action\DeleteCategoryByIdAction;
use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Container\AdminSection\Category\Task\FindCategoryByIdTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DeleteCategoryByIdActionTest extends KernelTestCase
{
    private DeleteCategoryByIdAction $deleteCategoryByIdAction;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $findCategoryByIdTask = self::getContainer()->get(FindCategoryByIdTask::class);
        $logger = self::getContainer()->get(LoggerInterface::class);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->deleteCategoryByIdAction = new DeleteCategoryByIdAction($findCategoryByIdTask, $logger);
        $this->deleteCategoryByIdAction->setEntityManager($this->entityManager);
    }

    public function testRunDeleteExistsCategory(): void
    {
        self::assertTrue($this->deleteCategoryByIdAction->run(1));
        self::assertNull($this->entityManager->find(Category::class, 1));
    }

    public function testRunDeleteNotExistsCategory(): void
    {
        self::assertFalse($this->deleteCategoryByIdAction->run(999));
    }
}
