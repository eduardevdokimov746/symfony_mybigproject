<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class CheckEmailControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/reset-password/check-email');

        self::assertResponseIsSuccessful();
    }

    public function testRequestNotGuest(): void
    {
        $client = self::createClient();

        $user = self::findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/reset-password/check-email');

        self::assertResponseRedirects();
    }
}
