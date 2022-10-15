<?php

declare(strict_types=1);

namespace App\Container\Profile\Test\Controller;

use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class IndexControllerTest extends WebTestCase
{
    public function testRequest(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(EntityManagerInterface::class)->find(User::class, 1);

        $client->loginUser($user);

        $client->request('GET', '/profile');

        $this->assertResponseIsSuccessful();
    }

    public function testRequestNotAuthenticated(): void
    {
        $client = self::createClient();

        $client->request('GET', '/profile');

        $this->assertResponseRedirects();
    }
}
