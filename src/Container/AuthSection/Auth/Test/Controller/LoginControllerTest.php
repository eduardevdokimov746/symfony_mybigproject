<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
    }

    public function testRequestPOST(): void
    {
        $client = self::createClient();

        $client->request('POST', '/login', [
            'login' => 'ens',
            'password' => 'ens',
        ]);

        /** @phpstan-ignore-next-line */
        self::assertStringNotContainsString('/login', $client->getInternalResponse()->getHeader('location'));
    }

    public function testRequestNotGuest(): void
    {
        $client = self::createClient();

        $user = self::findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/login');

        self::assertResponseRedirects();
    }
}
