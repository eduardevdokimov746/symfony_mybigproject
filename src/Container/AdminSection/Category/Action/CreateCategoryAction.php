<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Action;

use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Parent\Task;

class CreateCategoryAction extends Task
{
    public function run(string $ruName, string $enName, callable $callback = null): Category
    {
        $category = new Category($ruName, $enName);

        $this->call($callback, $category);

        $this->persistAndFlush($category);

        return $category;
    }
}
