<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Action;

use App\Container\Profile\Action\ChangePasswordAuthUserAction;
use App\Container\Profile\Data\DTO\ChangePasswordAuthUserDTO;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use App\Ship\Helper\Security;
use App\Ship\Parent\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ChangePasswordFromAuthUserActionTest extends KernelTestCase
{
    public function testRun(): void
    {
        $user = self::findUserFromDB();
        $dto = ChangePasswordAuthUserDTO::create([
            'oldPlainPassword' => $user->getPassword(),
            'newPlainPassword' => 'new password',
            'newPlainPasswordConfirmation' => 'new password',
        ]);

        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $tokenStorage = self::getContainer()->get(TokenStorageInterface::class);
        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main'));

        $security = self::getContainer()->get(Security::class);

        $changePasswordFromAuthUserAction = new ChangePasswordAuthUserAction($changeUserPasswordTask, $security);
        $updatedUser = $changePasswordFromAuthUserAction->run($dto);

        self::assertInstanceOf(User::class, $updatedUser);
        self::assertNotSame($dto->oldPlainPassword, $updatedUser->getPassword());

        /** @phpstan-ignore-next-line */
        self::assertNotSame($dto->oldPlainPassword, $security->getUser()->getPassword());
    }

    public function testRunExpectException(): void
    {
        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);

        $security = self::getContainer()->get(Security::class);
        $dto = ChangePasswordAuthUserDTO::create([
            'oldPlainPassword' => 'old password',
            'newPlainPassword' => 'new password',
            'newPlainPasswordConfirmation' => 'new password',
        ]);

        $changePasswordFromAuthUserAction = new ChangePasswordAuthUserAction($changeUserPasswordTask, $security);

        $this->expectExceptionMessage('authenticated');

        $changePasswordFromAuthUserAction->run($dto);
    }
}
