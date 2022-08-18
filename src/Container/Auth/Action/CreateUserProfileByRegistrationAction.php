<?php

namespace App\Container\Auth\Action;

use App\Container\Auth\Data\DTO\CreateUserProfileByRegistrationDTO;
use App\Container\Auth\Event\UserRegisteredEvent;
use App\Container\Auth\Exception\SaveByRegistrationException;
use App\Container\Auth\Task\CreateEmailVerification;
use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Action;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

class CreateUserProfileByRegistrationAction extends Action
{
    public function __construct(
        private CreateUserTask           $createUserTask,
        private CreateProfileTask        $createProfileTask,
        private CreateEmailVerification  $createEmailVerification,
        private EntityManagerInterface   $entityManager,
        private EventDispatcherInterface $eventDispatcher
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

            $this->createEmailVerification->run(
                $user,
                $this->generateVerificationCode(),
                new DateTimeImmutable('+1 mount')
            );

            $this->entityManager->commit();
        } catch (Throwable) {
            $this->entityManager->rollback();
            throw new SaveByRegistrationException();
        }

        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);

        return $user;
    }

    private function generateVerificationCode(): string
    {
        return md5(microtime());
    }
}