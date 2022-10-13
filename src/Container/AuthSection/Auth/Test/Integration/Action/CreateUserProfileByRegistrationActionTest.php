<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Integration\Action;

use App\Container\AuthSection\Auth\Action\CreateUserProfileByRegistrationAction;
use App\Container\AuthSection\Auth\Data\DTO\CreateUserProfileByRegistrationDTO;
use App\Container\AuthSection\Auth\Exception\SaveByRegistrationException;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\CreateUserTask;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CreateUserProfileByRegistrationActionTest extends KernelTestCase
{
    private CreateUserProfileByRegistrationAction $createUserProfileByRegistrationAction;

    protected function setUp(): void
    {
        $createUserTask = self::getContainer()->get(CreateUserTask::class);
        $createProfileTask = self::getContainer()->get(CreateProfileTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $logger = $this->createStub(LoggerInterface::class);
        $eventDispatcher = $this->createStub(EventDispatcherInterface::class);

        $this->createUserProfileByRegistrationAction = new CreateUserProfileByRegistrationAction(
            $createUserTask,
            $createProfileTask,
            $entityManager,
            $eventDispatcher,
            $logger
        );
    }

    public function testRun(): void
    {
        $createUserProfileByRegistrationDTO = CreateUserProfileByRegistrationDTO::fromArray([
            'login' => 'user',
            'email' => 'user@mail.com',
            'plainPassword' => 'password',
        ]);

        $user = $this->createUserProfileByRegistrationAction->run($createUserProfileByRegistrationDTO);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('user', $user->getLogin());
        $this->assertSame('user@mail.com', $user->getEmail());
        $this->assertInstanceOf(Profile::class, $user->getProfile());
    }

    public function testRunExpectException(): void
    {
        $createUserProfileByRegistrationDTO = CreateUserProfileByRegistrationDTO::fromArray([
            'login' => 'ens',
            'email' => 'ens@mail.com',
            'plainPassword' => 'password',
        ]);

        $this->expectException(SaveByRegistrationException::class);

        $this->createUserProfileByRegistrationAction->run($createUserProfileByRegistrationDTO);
    }
}
