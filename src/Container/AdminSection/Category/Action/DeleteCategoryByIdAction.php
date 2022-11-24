<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Action;

use App\Container\AdminSection\Category\Exception\CategoryNotFoundException;
use App\Container\AdminSection\Category\Task\FindCategoryByIdTask;
use App\Ship\Parent\Action;
use Psr\Log\LoggerInterface;

class DeleteCategoryByIdAction extends Action
{
    public function __construct(
        private FindCategoryByIdTask $findCategoryByIdTask,
        private LoggerInterface $logger,
    ) {
    }

    public function run(int $id): bool
    {
        try {
            $this->remove($this->findCategoryByIdTask->run($id));
            $this->flush();

            return true;
        } catch (CategoryNotFoundException $e) {
            $this->logger->info($e->getMessage());
        }

        return false;
    }
}
