<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Test\Controller;

use App\Ship\Parent\Test\WebTestCase;

class CreateControllerTest extends WebTestCase
{
    public function testGET(): void
    {
        $client = self::createClient();

        $client->request('GET', '/admin/categories/create');

        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPOST(string $ruName, string $enName, ?bool $active): void
    {
        $client = self::createClient();

        $client->request('POST', '/admin/categories/create', [
            'category_form' => [
                'ru_name' => $ruName,
                'en_name' => $enName,
                'active' => $active,
            ],
        ]);

        self::assertTrue($client->getRequest()->getSession()->getFlashBag()->has('success'));
    }

    /**
     * @return list<list<null|bool|string>>
     */
    public function dataProvider(): array
    {
        return [
            ['тестовая категория', 'test category', true],
            ['тестовая категория', 'test category', false],
            ['тестовая категория', 'test category', null],
        ];
    }
}
