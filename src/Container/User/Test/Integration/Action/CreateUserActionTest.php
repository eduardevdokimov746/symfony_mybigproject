<?php

declare(strict_types=1);

namespace App\Container\User\Test\Integration\Action;

use App\Container\User\Action\CreateUserAction;
use App\Container\User\Data\DTO\CreateUserDTO;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserActionTest extends KernelTestCase
{
    private CreateUserAction $createUserAction;
    private CreateUserDTO $createUserDTO;

    protected function setUp(): void
    {
        $createUserTask = self::getContainer()->get(CreateUserTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->createUserAction = new CreateUserAction($createUserTask);
        $this->createUserAction->setEntityManager($entityManager);

        $this->createUserDTO = CreateUserDTO::create([
            'login' => 'test',
            'email' => 'test@mail.com',
            'plainPassword' => '123',
        ]);
    }

    public function testRun(): void
    {
        $user = $this->createUserAction->run($this->createUserDTO);

        self::assertSame(RoleEnum::User, $user->getRole());
    }

    public function testRunWithCallback(): void
    {
        $user = $this->createUserAction->run(
            $this->createUserDTO,
            static fn (User $user) => $user->setRole(RoleEnum::Admin)
        );

        self::assertSame(RoleEnum::User, $user->getRole());
    }
}
