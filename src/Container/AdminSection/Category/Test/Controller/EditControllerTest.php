<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class EditControllerTest extends WebTestCase
{
    public function testInvokeGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/categories/1');

        self::assertResponseIsSuccessful();
    }

    public function testInvokeGETCategoryNotFound(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/categories/999');

        self::assertResponseStatusCodeSame(404);
    }
}
