<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Action;

use App\Container\AuthSection\Auth\Data\DTO\RegisterDTO;
use App\Container\AuthSection\Auth\Event\UserRegisteredEvent;
use App\Container\AuthSection\Auth\Exception\SaveByRegistrationException;
use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Action;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

class RegisterAction extends Action
{
    public function __construct(
        private CreateUserTask $createUserTask,
        private CreateProfileTask $createProfileTask,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $authLogger
    ) {
    }

    public function run(RegisterDTO $dto): User
    {
        $this->beginTransaction();

        try {
            $user = $this->createUserTask->run($dto->login, $dto->email, $dto->plainPassword);

            $this->createProfileTask->run($user);

            $this->commit();
        } catch (Throwable) {
            $this->rollback();

            throw new SaveByRegistrationException();
        }

        $this->authLogger->info('New user registered', [
            'id' => $user->getId(),
            'userIdentifier' => $user->getUserIdentifier(),
        ]);

        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);

        return $user;
    }
}
