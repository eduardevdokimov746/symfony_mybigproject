<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Integration\Action;

use App\Container\AdminSection\Category\Action\CreateCategoryAction;
use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class CreateCategoryActionTest extends KernelTestCase
{
    private CreateCategoryAction $createCategoryAction;

    protected function setUp(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->createCategoryAction = new CreateCategoryAction();
        $this->createCategoryAction->setEntityManager($entityManager);
    }

    public function testRun(): void
    {
        $category = $this->createCategoryAction->run('тестовая категория', 'test category');

        self::assertIsInt($category->getId());
        self::assertSame('тестовая категория', $category->getRuName());
        self::assertSame('test category', $category->getEnName());
    }

    public function testRunWithCallback(): void
    {
        $callback = static function (Category $category): void {
            $category->setActive(false);
        };

        $category = $this->createCategoryAction->run('тестовая категория', 'test category', $callback);

        self::assertIsInt($category->getId());
        self::assertSame('тестовая категория', $category->getRuName());
        self::assertSame('test category', $category->getEnName());
        self::assertFalse($category->isActive());
    }
}
