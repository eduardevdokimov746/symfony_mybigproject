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

        $this->assertResponseIsSuccessful();
    }

    public function testRequestPOST(): void
    {
        $client = self::createClient();

        $client->request('POST', '/registration', [
            'login' => 'user',
            'email' => 'user@mail.com',
            'plainPassword' => 'password',
        ]);

        $this->assertStringNotContainsString('/registration', $client->getInternalResponse()->getHeader('location'));
    }

    public function testRequestNotGuest(): void
    {
        $client = self::createClient();

        $user = $this->findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/registration');

        $this->assertResponseRedirects();
    }
}
