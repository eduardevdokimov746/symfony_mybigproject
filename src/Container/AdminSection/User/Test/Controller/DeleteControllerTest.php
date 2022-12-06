<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class DeleteControllerTest extends WebTestCase
{
    public function testDELETE(): void
    {
        $client = self::createClient();

        $client->request('DELETE', 'admin/users/1');

        self::assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));
    }

    public function testDELETENotExistsUser(): void
    {
        $client = self::createClient();

        $client->request('DELETE', 'admin/users/999');

        self::assertFalse($client->getRequest()->getSession()->getFlashBag()->has('success'));
    }
}
