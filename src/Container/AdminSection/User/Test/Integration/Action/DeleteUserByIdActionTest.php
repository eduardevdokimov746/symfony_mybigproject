<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\Test\Integration\Action;

use App\Container\AdminSection\User\Action\DeleteUserByIdAction;
use App\Container\User\Task\FindUserByIdTask;
use App\Ship\Parent\Test\KernelTestCase;
use App\Ship\Service\ImageStorage\ImageStorage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DeleteUserByIdActionTest extends KernelTestCase
{
    private DeleteUserByIdAction $deleteUserByIdAction;

    protected function setUp(): void
    {
        $findUserByIdTask = self::getContainer()->get(FindUserByIdTask::class);
        $logger = self::getContainer()->get(LoggerInterface::class);
        $imageStorage = self::getContainer()->get(ImageStorage::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->deleteUserByIdAction = new DeleteUserByIdAction($findUserByIdTask, $logger, $imageStorage);
        $this->deleteUserByIdAction->setEntityManager($entityManager);
    }

    public function testRun(): void
    {
        self::assertTrue($this->deleteUserByIdAction->run(1));
    }

    public function testRunNotExistsUser(): void
    {
        self::assertFalse($this->deleteUserByIdAction->run(999));
    }
}
