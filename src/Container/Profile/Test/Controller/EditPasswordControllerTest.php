<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class EditPasswordControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $user = self::findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/profile/edit-password');

        self::assertResponseIsSuccessful();
    }

    public function testRequestPOST(): void
    {
        $client = self::createClient();

        $user = self::findUserFromDB();

        $client->loginUser($user);

        $client->request('POST', '/profile/edit-password', [
            'oldPlainPassword' => 'ens',
            'newPlainPassword' => 'new-password',
            'newPlainPasswordConfirmation' => 'new-password',
        ]);

        self::assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));
    }

    public function testRequestNotAuthenticated(): void
    {
        $client = self::createClient();

        $client->request('GET', '/profile/edit-password');

        self::assertResponseRedirects();
    }
}
