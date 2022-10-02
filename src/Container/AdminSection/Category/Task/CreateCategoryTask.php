<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Task;

use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Ship\Parent\Task;
use Doctrine\ORM\EntityManagerInterface;

class CreateCategoryTask extends Task
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function run(string $ruName, string $enName): Category
    {
        $category = new Category($ruName, $enName);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}
