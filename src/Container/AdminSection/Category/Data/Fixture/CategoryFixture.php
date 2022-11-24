<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Data\Fixture;

use App\Container\AdminSection\Category\Action\CreateCategoryAction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function __construct(
        private CreateCategoryAction $createCategoryAction
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createCategoryAction->lazy()->run('Спорт', 'Sport');
        $this->createCategoryAction->lazy()->run('Экшен', 'Action');
        $this->createCategoryAction->lazy()->run('Стратегия', 'Strategy');
        $this->createCategoryAction->lazy()->run('Приключение', 'Adventure');

        $manager->flush();
    }
}
