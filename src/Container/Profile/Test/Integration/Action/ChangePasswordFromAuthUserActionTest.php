<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Action;

use App\Container\Profile\Action\ChangePasswordFromAuthUserAction;
use App\Container\User\Entity\Doc\User;
use App\Container\User\Task\ChangeUserPasswordTask;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ChangePasswordFromAuthUserActionTest extends KernelTestCase
{
    public function testRun(): void
    {
        /** @var User $user */
        $user = self::getContainer()->get(EntityManagerInterface::class)->find(User::class, 1);
        $oldPassword = $user->getPassword();

        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $tokenStorage = self::getContainer()->get(TokenStorageInterface::class);
        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main'));

        $changePasswordFromAuthUserAction = new ChangePasswordFromAuthUserAction($changeUserPasswordTask, $tokenStorage);
        $updatedUser = $changePasswordFromAuthUserAction->run('new password');

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertNotSame($oldPassword, $updatedUser->getPassword());
        $this->assertNotSame($oldPassword, $tokenStorage->getToken()->getUser()->getPassword());
    }

    public function testRunExpectException(): void
    {
        $changeUserPasswordTask = self::getContainer()->get(ChangeUserPasswordTask::class);
        $tokenStorage = self::getContainer()->get(TokenStorageInterface::class);

        $changePasswordFromAuthUserAction = new ChangePasswordFromAuthUserAction($changeUserPasswordTask, $tokenStorage);

        $this->expectExceptionMessage('authenticated');

        $changePasswordFromAuthUserAction->run('new password');
    }
}
