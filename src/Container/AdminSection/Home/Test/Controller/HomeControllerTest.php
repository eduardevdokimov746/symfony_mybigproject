<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Home\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testGET(): void
    {
        $client = self::createClient();

        $client->request('GET', 'admin');

        self::assertResponseIsSuccessful();
    }
}
