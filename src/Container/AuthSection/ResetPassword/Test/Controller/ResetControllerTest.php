<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class ResetControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/reset-password/reset');

        $this->assertResponseRedirects();
    }

    public function testRequestNotGuest(): void
    {
        $client = self::createClient();

        $user = $this->findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/reset-password/reset');

        $this->assertResponseRedirects();
    }
}
