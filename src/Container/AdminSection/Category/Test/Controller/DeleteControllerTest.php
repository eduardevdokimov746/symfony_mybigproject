<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class DeleteControllerTest extends WebTestCase
{
    public function testExistsCategory(): void
    {
        $client = self::createClient();

        $client->request('DELETE', '/admin/categories/1');

        self::assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));
    }

    public function testNotExistsCategory(): void
    {
        $client = self::createClient();

        $client->request('DELETE', '/admin/categories/999');

        self::assertFalse($client->getRequest()->getSession()->getFlashBag()->has('success'));
    }
}
