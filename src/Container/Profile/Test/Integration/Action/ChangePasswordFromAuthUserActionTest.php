<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Action;

use App\Container\Profile\Action\ChangePasswordFromAuthUserAction;
use App\Container\Profile\Data\DTO\ChangePasswordFromAuthUserDTO;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Ship\Parent\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ChangePasswordFromAuthUserActionTest extends KernelTestCase
{
    public function testRun(): void
    {
        $user = $this->findUserFromDB();
        $dto = ChangePasswordFromAuthUserDTO::create([
            'oldPlainPassword' => $user->getPassword(),
            'newPlainPassword' => 'new password',
            'newPlainPasswordConfirmation' => 'new password',
        ]);

        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $tokenStorage = self::getContainer()->get(TokenStorageInterface::class);
        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main'));

        $changePasswordFromAuthUserAction = new ChangePasswordFromAuthUserAction($changeUserPasswordTask, $tokenStorage);
        $updatedUser = $changePasswordFromAuthUserAction->run($dto);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertNotSame($dto->oldPlainPassword, $updatedUser->getPassword());
        $this->assertNotSame($dto->oldPlainPassword, $tokenStorage->getToken()->getUser()->getPassword());
    }

    public function testRunExpectException(): void
    {
        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $tokenStorage = self::getContainer()->get(TokenStorageInterface::class);
        $dto = ChangePasswordFromAuthUserDTO::create([
            'oldPlainPassword' => 'old password',
            'newPlainPassword' => 'new password',
            'newPlainPasswordConfirmation' => 'new password',
        ]);

        $changePasswordFromAuthUserAction = new ChangePasswordFromAuthUserAction($changeUserPasswordTask, $tokenStorage);

        $this->expectExceptionMessage('authenticated');

        $changePasswordFromAuthUserAction->run($dto);
    }
}
