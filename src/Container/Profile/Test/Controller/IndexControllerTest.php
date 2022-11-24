<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testRequest(): void
    {
        $client = self::createClient();

        $user = self::findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/profile');

        self::assertResponseIsSuccessful();
    }

    public function testRequestNotAuthenticated(): void
    {
        $client = self::createClient();

        $client->request('GET', '/profile');

        self::assertResponseRedirects();
    }
}
