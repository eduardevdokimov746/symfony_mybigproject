<?php

declare(strict_types=1);

namespace App\Container\User\Action;

use App\Container\User\Data\DTO\CreateAdminDTO;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Action;

class CreateAdminAction extends Action
{
    public function __construct(
        private CreateUserTask $createUserTask
    ) {
    }

    public function run(CreateAdminDTO $dto, callable $callback = null): User
    {
        $user = $this->createUserTask->lazy()->run($dto->login, $dto->email, $dto->plainPassword, $callback);

        $user->setRole(RoleEnum::Admin);

        $this->flush();

        return $user;
    }
}
