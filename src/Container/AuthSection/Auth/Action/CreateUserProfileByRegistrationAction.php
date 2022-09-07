<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Action;

use App\Container\AuthSection\Auth\Data\DTO\CreateUserProfileByRegistrationDTO;
use App\Container\AuthSection\Auth\Event\UserRegisteredEvent;
use App\Container\AuthSection\Auth\Exception\SaveByRegistrationException;
use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Action;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

class CreateUserProfileByRegistrationAction extends Action
{
    public function __construct(
        private CreateUserTask           $createUserTask,
        private CreateProfileTask        $createProfileTask,
        private EntityManagerInterface   $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface          $authLogger,
    )
    {
    }

    public function run(CreateUserProfileByRegistrationDTO $userProfileByRegistrationDTO): User
    {
        $this->entityManager->beginTransaction();

        try {
            $user = $this->createUserTask->run(
                $userProfileByRegistrationDTO->login,
                $userProfileByRegistrationDTO->email,
                $userProfileByRegistrationDTO->plainPassword
            );

            $this->createProfileTask->run($user);

            $this->entityManager->commit();
        } catch (Throwable) {
            $this->entityManager->rollback();
            throw new SaveByRegistrationException();
        }

        $this->authLogger->info('New user registered', [
            'id'             => $user->getId(),
            'userIdentifier' => $user->getUserIdentifier()
        ]);

        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);

        return $user;
    }
}