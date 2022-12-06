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

class CreateControllerTest extends WebTestCase
{
    public function testGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/users/create');

        self::assertResponseIsSuccessful();
    }

    public function testPOST(): void
    {
        $client = self::createClient();
        $packages = self::getContainer()->get(Packages::class);
        $publicDir = self::getContainer()->getParameter('app_public_dir');
        $file = File::createTmpImage(ImageResize::TMP_PREFIX, 600, 500);
        $uploadedFile = self::createStub(UploadedFile::class);
        $uploadedFile->method('getPathname')->willReturn($file);

        $client->request(
            'POST',
            '/admin/users/create',
            [
                'user_form' => [
                    'role' => RoleEnum::Admin->value,
                    'login' => 'test',
                    'email' => 'test@mail.com',
                    'plain_password' => '123',
                    'email_verified' => true,
                    'first_name' => 'firstName',
                    'last_name' => 'lastName',
                    'patronymic' => 'patronymic',
                    'about' => 'about',
                ],
            ],
            [
                'user_form' => [
                    'avatar' => $uploadedFile,
                ],
            ]
        );

        $createdUser = self::findUserFromDB(['login' => 'test', 'email' => 'test@mail.com']);
        $avatar = $createdUser->getProfile()->getAvatar();
        $avatarPath = $publicDir.$packages->getPackage(ImageStorageEnum::Avatar->value)->getUrl($avatar);

        self::assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));
        self::assertEquals(RoleEnum::Admin, $createdUser->getRole());
        self::assertFalse($createdUser->isActive());
        self::assertTrue($createdUser->isEmailVerified());
        self::assertSame('firstName', $createdUser->getProfile()->getFirstName());
        self::assertSame('lastName', $createdUser->getProfile()->getLastName());
        self::assertSame('patronymic', $createdUser->getProfile()->getPatronymic());
        self::assertSame('about', $createdUser->getProfile()->getAbout());
        self::assertFileExists($avatarPath);

        unlink($avatarPath);
    }
}
