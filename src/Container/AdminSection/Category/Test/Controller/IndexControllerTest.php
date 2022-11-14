<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/categories');

        self::assertResponseIsSuccessful();
    }

    public function testGETNormalPage(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/categories', ['p' => 1]);

        self::assertResponseIsSuccessful();
    }

    public function testGETNotValidPage(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/categories', ['p' => 'test']);

        self::assertResponseIsSuccessful();
    }
}
