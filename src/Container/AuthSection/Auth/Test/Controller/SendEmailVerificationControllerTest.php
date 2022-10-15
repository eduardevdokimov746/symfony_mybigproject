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

        $this->assertEmailCount(1);
        $this->assertResponseIsSuccessful();
    }

    public function testRequestNotAuthenticated(): void
    {
        $client = self::createClient();

        $client->request('GET', '/send-email-verification');

        $this->assertResponseRedirects();
    }
}
