<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Integration\Action;

use App\Container\Profile\Action\UpdateProfileAuthUserAction;
use App\Container\Profile\Data\DTO\UpdateProfileAuthUserDTO;
use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\Profile\Task\UpdateProfileTask;
use App\Container\User\Entity\Doc\User;
use App\Ship\Helper\Security;
use App\Ship\Parent\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UpdateProfileAuthUserActionTest extends KernelTestCase
{
    public function testRun(): void
    {
        $user = self::findUserFromDB();

        $oldFirstName = $user->getProfile()->getFirstName();
        $oldLastName = $user->getProfile()->getLastName();
        $oldPatronymic = $user->getProfile()->getPatronymic();
        $oldAbout = $user->getProfile()->getAbout();

        $tokenStorage = self::getContainer()->get(TokenStorageInterface::class);
        $tokenStorage->setToken(new UsernamePasswordToken($user, 'main'));

        $security = self::getContainer()->get(Security::class);
        $updateProfileFullNameAndAboutTask = self::getContainer()->get(UpdateProfileTask::class);
        $changeAvatarTask = self::getContainer()->get(ChangeAvatarTask::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $updateProfileAuthUserAction = new UpdateProfileAuthUserAction(
            $security,
            $updateProfileFullNameAndAboutTask,
            $changeAvatarTask
        );
        $updateProfileAuthUserAction->setEntityManager($entityManager);

        $updateProfileAuthUserDTO = UpdateProfileAuthUserDTO::create([
            'firstName' => 'new firstName',
            'lastName' => 'new lastName',
            'patronymic' => 'new patronymic',
            'about' => 'new about',
        ]);

        $updateProfileAuthUserAction->run($updateProfileAuthUserDTO);

        /** @var User $authUser */
        $authUser = $security->getUser();

        self::assertNotSame($oldFirstName, $authUser->getProfile()->getFirstName());
        self::assertNotSame($oldLastName, $authUser->getProfile()->getLastName());
        self::assertNotSame($oldPatronymic, $authUser->getProfile()->getPatronymic());
        self::assertNotSame($oldAbout, $authUser->getProfile()->getAbout());
    }
}
