<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Integration\Task;

use App\Container\AdminSection\Category\Task\CreateCategoryTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class CreateCategoryTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $createCategoryTask = new CreateCategoryTask($entityManager);

        $result = $createCategoryTask->run('тестовая категория', 'test category');

        self::assertSame('тестовая категория', $result->getRuName());
        self::assertSame('test category', $result->getEnName());
    }
}
