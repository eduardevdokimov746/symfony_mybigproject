<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Data\Fixture;

use App\Container\AdminSection\Category\Task\CreateCategoryTask;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function __construct(
        private CreateCategoryTask $createCategoryTask
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createCategoryTask->run('Спорт', 'Sport');
        $this->createCategoryTask->run('Экшен', 'Action');
        $this->createCategoryTask->run('Стратегия', 'Strategy');
        $this->createCategoryTask->run('Приключение', 'Adventure');
    }
}
