<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\Test\Integration\Action;

use App\Container\AdminSection\User\Action\GetAllUsersWithPaginationAction;
use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Ship\Parent\Test\KernelTestCase;

class GetAllUsersWithPaginationActionTest extends KernelTestCase
{
    private GetAllUsersWithPaginationAction $getAllUsersWithPaginationAction;

    protected function setUp(): void
    {
        $userRepository = self::getContainer()->get(UserRepository::class);

        $this->getAllUsersWithPaginationAction = new GetAllUsersWithPaginationAction($userRepository);
    }

    public function testRun(): void
    {
        $users = $this->getAllUsersWithPaginationAction->run(1);

        self::assertLessThanOrEqual(GetAllUsersWithPaginationAction::MAX_RESULTS, count($users));
    }

    public function testRunNotFoundPage(): void
    {
        $users = $this->getAllUsersWithPaginationAction->run(999);

        self::assertCount(0, $users);
    }
}
