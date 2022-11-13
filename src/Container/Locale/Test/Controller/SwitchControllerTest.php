<?php

declare(strict_types=1);

namespace App\Container\Locale\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class SwitchControllerTest extends WebTestCase
{
    public function testRequest(): void
    {
        $client = self::createClient();

        $client->request('GET', '/locale/switch');

        self::assertResponseRedirects();
    }
}
