<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\Test\Controller;

use App\Container\User\Enum\RoleEnum;
use App\Ship\Contract\ImageResize;
use App\Ship\Helper\File;
use App\Ship\Parent\Test\WebTestCase;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EditControllerTest extends WebTestCase
{
    public function testGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/users/1');

        self::assertResponseIsSuccessful();
    }

    public function testGETUserNotFound(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/users/999');

        self::assertResponseStatusCodeSame(404);
    }

    public function testPUT(): void
    {
        $client = self::createClient();
        $packages = self::getContainer()->get(Packages::class);
        $publicDir = self::getContainer()->getParameter('app_public_dir');
        $file = File::createTmpImage(ImageResize::TMP_PREFIX, 600, 500);
        $uploadedFile = self::createStub(UploadedFile::class);
        $uploadedFile->method('getPathname')->willReturn($file);

        $user = self::findUserFromDB();
        $oldPassword = $user->getPassword();

        $client->request(
            'PUT',
            '/admin/users/1',
            [
                'user_form' => [
                    'role' => RoleEnum::Admin->value,
                    'login' => 'test',
                    'email' => 'test@mail.com',
                    'plain_password' => 'new password',
                    'email_verified' => true,
                    'first_name' => 'new-firstName',
                    'last_name' => 'new-lastName',
                    'patronymic' => 'new-patronymic',
                    'about' => 'new about',
                ],
            ],
            [
                'user_form' => [
                    'avatar' => $uploadedFile,
                ],
            ]
        );

        $updatedUser = self::findUserFromDB();
        $avatar = $updatedUser->getProfile()->getAvatar();
        $avatarPath = $publicDir.$packages->getPackage(ImageStorageEnum::Avatar->value)->getUrl($avatar);

        self::assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));
        self::assertEquals(RoleEnum::Admin, $updatedUser->getRole());
        self::assertFalse($updatedUser->isActive());
        self::assertTrue($updatedUser->isEmailVerified());
        self::assertNotEquals($oldPassword, $updatedUser->getPassword());
        self::assertSame('new-firstName', $updatedUser->getProfile()->getFirstName());
        self::assertSame('new-lastName', $updatedUser->getProfile()->getLastName());
        self::assertSame('new-patronymic', $updatedUser->getProfile()->getPatronymic());
        self::assertSame('new about', $updatedUser->getProfile()->getAbout());
        self::assertFileExists($avatarPath);

        unlink($avatarPath);
    }
}
