<?php

declare(strict_types=1);

namespace App\Ship\Test\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ValidationRedirectControllerTest extends WebTestCase
{
    public function testInvoke(): void
    {
        $client = self::createClient(server: ['HTTP_REFERER' => 'http://localhost/login', 'HTTP_ACCEPT_LANGUAGE' => 'ru']);

        $client->request('GET', '/test/validation_redirect');

        $this->assertResponseRedirects('/login');
    }
}
