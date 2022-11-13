<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class SendEmailVerificationControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $user = $this->findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/send-email-verification');

        self::assertEmailCount(1);
        self::assertResponseIsSuccessful();
    }

    public function testRequestNotAuthenticated(): void
    {
        $client = self::createClient();

        $client->request('GET', '/send-email-verification');

        self::assertResponseRedirects();
    }
}
