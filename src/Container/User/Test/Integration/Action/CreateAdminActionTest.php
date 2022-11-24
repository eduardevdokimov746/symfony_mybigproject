<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Action;

use App\Container\User\Action\CreateAdminAction;
use App\Container\User\Data\DTO\CreateAdminDTO;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class CreateAdminActionTest extends KernelTestCase
{
    private CreateAdminAction $createAdminAction;
    private CreateAdminDTO $createAdminDTO;

    protected function setUp(): void
    {
        $createUserTask = self::getContainer()->get(CreateUserTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->createAdminAction = new CreateAdminAction($createUserTask);
        $this->createAdminAction->setEntityManager($entityManager);

        $this->createAdminDTO = CreateAdminDTO::create([
            'login' => 'test',
            'email' => 'test@mail.com',
            'plainPassword' => '123',
        ]);
    }

    public function testRun(): void
    {
        $user = $this->createAdminAction->run($this->createAdminDTO);

        self::assertSame(RoleEnum::Admin, $user->getRole());
    }

    public function testRunWithCallback(): void
    {
        $user = $this->createAdminAction->run(
            $this->createAdminDTO,
            static fn (User $user) => $user->setRole(RoleEnum::User)
        );

        self::assertSame(RoleEnum::Admin, $user->getRole());
    }
}
