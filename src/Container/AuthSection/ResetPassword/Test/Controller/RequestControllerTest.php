<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class RequestControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/reset-password');

        self::assertResponseIsSuccessful();
    }

    public function testRequestPOST(): void
    {
        $client = self::createClient();

        $client->request('POST', '/reset-password', [
            'email' => 'ens@mail.com',
        ]);

        self::assertStringContainsString(
            '/reset-password/check-email',
            $client->getInternalResponse()->getHeader('location') /** @phpstan-ignore-line */
        );
    }

    public function testRequestNotGuest(): void
    {
        $client = self::createClient();

        $user = self::findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/reset-password');

        self::assertResponseRedirects();
    }
}
