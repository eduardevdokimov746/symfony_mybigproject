<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/registration');

        self::assertResponseIsSuccessful();
    }

    public function testRequestPOST(): void
    {
        $client = self::createClient();

        $client->request('POST', '/registration', [
            'login' => 'user',
            'email' => 'user@mail.com',
            'plainPassword' => 'password',
        ]);

        /** @phpstan-ignore-next-line */
        self::assertStringNotContainsString('/registration', $client->getInternalResponse()->getHeader('location'));
    }

    public function testRequestNotGuest(): void
    {
        $client = self::createClient();

        $user = self::findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/registration');

        self::assertResponseRedirects();
    }
}
