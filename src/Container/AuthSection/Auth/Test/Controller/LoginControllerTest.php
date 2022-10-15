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

        $this->assertResponseIsSuccessful();
    }

    public function testRequestPOST(): void
    {
        $client = self::createClient();

        $client->request('POST', '/login', [
            'login' => 'ens',
            'password' => 'ens',
        ]);

        $this->assertStringNotContainsString('/login', $client->getInternalResponse()->getHeader('location'));
    }

    public function testRequestNotGuest(): void
    {
        $client = self::createClient();

        $user = $this->findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/login');

        $this->assertResponseRedirects();
    }
}
