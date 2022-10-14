<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Action;

use App\Container\Profile\Action\UpdateProfileFromAuthUserAction;
use App\Container\Profile\Data\DTO\UpdateProfileFromAuthUserDTO;
use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\Profile\Task\DeleteAvatarTask;
use App\Container\Profile\Task\UpdateProfileFullNameAndAboutTask;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;

class UpdateProfileFromAuthUserActionTest extends KernelTestCase
{
    public function testRun(): void
    {
        $user = $this->findUserFromDB();

        $oldFirstName = $user->getProfile()->getFirstName();
        $oldLastName = $user->getProfile()->getLastName();
        $oldPatronymic = $user->getProfile()->getPatronymic();
        $oldAbout = $user->getProfile()->getAbout();

        $tokenStorage = self::getContainer()->get(TokenStorageInterface::class);
        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main'));

        $security = self::getContainer()->get(Security::class);
        $updateProfileFullNameAndAboutTask = self::getContainer()->get(UpdateProfileFullNameAndAboutTask::class);
        $changeAvatarTask = self::getContainer()->get(ChangeAvatarTask::class);
        $deleteAvatarTask = self::getContainer()->get(DeleteAvatarTask::class);

        $updateProfileFromAuthUserAction = new UpdateProfileFromAuthUserAction(
            $security,
            $updateProfileFullNameAndAboutTask,
            $changeAvatarTask,
            $deleteAvatarTask
        );

        $updateProfileFromAuthUserDTO = UpdateProfileFromAuthUserDTO::fromArray([
            'firstName' => 'new firstName',
            'lastName' => 'new lastName',
            'patronymic' => 'new patronymic',
            'about' => 'new about',
        ]);

        $updateProfileFromAuthUserAction->run($updateProfileFromAuthUserDTO);

        /** @var User $authUser */
        $authUser = $security->getUser();

        $this->assertNotSame($oldFirstName, $authUser->getProfile()->getFirstName());
        $this->assertNotSame($oldLastName, $authUser->getProfile()->getLastName());
        $this->assertNotSame($oldPatronymic, $authUser->getProfile()->getPatronymic());
        $this->assertNotSame($oldAbout, $authUser->getProfile()->getAbout());
    }
}
