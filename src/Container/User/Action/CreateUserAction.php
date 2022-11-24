<?php

declare(strict_types=1);

namespace App\Container\User\Action;

use App\Container\User\Data\DTO\CreateUserDTO;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Enum\RoleEnum;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Action;

class CreateUserAction extends Action
{
    public function __construct(
        private CreateUserTask $createUserTask
    ) {
    }

    public function run(CreateUserDTO $dto, callable $callback = null): User
    {
        $user = $this->createUserTask->lazy()->run($dto->login, $dto->email, $dto->plainPassword, $callback);

        $user->setRole(RoleEnum::User);

        $this->flush();

        return $user;
    }
}
