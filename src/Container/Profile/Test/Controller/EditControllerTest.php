<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class EditControllerTest extends WebTestCase
{
    public function testRequestGET(): void
    {
        $client = self::createClient();

        $user = $this->findUserFromDB();

        $client->loginUser($user);

        $client->request('GET', '/profile/edit');

        self::assertResponseIsSuccessful();
    }

    public function testRequestPOST(): void
    {
        $client = self::createClient();

        $user = $this->findUserFromDB();

        $client->loginUser($user);

        $client->request('PATCH', '/profile/edit', [
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'patronymic' => 'patronymic',
            'about' => 'about',
        ]);

        self::assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));
    }

    public function testRequestNotAuthenticated(): void
    {
        $client = self::createClient();

        $client->request('GET', '/profile/edit');

        self::assertResponseRedirects();
    }
}
