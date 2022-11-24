<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Integration\Action;

use App\Container\AuthSection\Auth\Action\RegisterAction;
use App\Container\AuthSection\Auth\Data\DTO\RegisterDTO;
use App\Container\AuthSection\Auth\Exception\SaveByRegistrationException;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\Profile\Task\CreateProfileTask;
use App\Container\User\Task\CreateUserTask;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegisterActionTest extends KernelTestCase
{
    private RegisterAction $registerAction;

    protected function setUp(): void
    {
        $createUserTask = self::getContainer()->get(CreateUserTask::class);
        $createProfileTask = self::getContainer()->get(CreateProfileTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $logger = self::createStub(LoggerInterface::class);
        $eventDispatcher = self::createStub(EventDispatcherInterface::class);

        $this->registerAction = new RegisterAction(
            $createUserTask,
            $createProfileTask,
            $eventDispatcher,
            $logger
        );

        $this->registerAction->setEntityManager($entityManager);
    }

    public function testRun(): void
    {
        $dto = RegisterDTO::create([
            'login' => 'user',
            'email' => 'user@mail.com',
            'plainPassword' => 'password',
        ]);

        $user = $this->registerAction->run($dto);

        self::assertSame('user', $user->getLogin());
        self::assertSame('user@mail.com', $user->getEmail());
        self::assertInstanceOf(Profile::class, $user->getProfile());
    }

    public function testRunExpectException(): void
    {
        $dto = RegisterDTO::create([
            'login' => 'ens',
            'email' => 'ens@mail.com',
            'plainPassword' => 'password',
        ]);

        $this->expectException(SaveByRegistrationException::class);

        $this->registerAction->run($dto);
    }
}
