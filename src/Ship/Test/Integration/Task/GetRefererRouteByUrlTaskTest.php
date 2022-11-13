<?php

declare(strict_types=1);

namespace App\Ship\Test\Integration\Task;

use App\Ship\Parent\Test\KernelTestCase;
use App\Ship\Task\GetRefererRouteByUrlTask;
use Symfony\Component\Routing\RouterInterface;

class GetRefererRouteByUrlTaskTest extends KernelTestCase
{
    private GetRefererRouteByUrlTask $refererRouteByUrlTask;

    protected function setUp(): void
    {
        $router = self::getContainer()->get(RouterInterface::class);

        $this->refererRouteByUrlTask = new GetRefererRouteByUrlTask($router);
    }

    /**
     * @dataProvider badRefererUrlProvider
     */
    public function testRunExpectNull(string $refererUrl): void
    {
        self::assertNull($this->refererRouteByUrlTask->run($refererUrl));
    }

    /**
     * @dataProvider badRefererUrlProvider
     */
    public function testGetOnlyExpectNull(string $refererUrl): void
    {
        self::assertNull($this->refererRouteByUrlTask->getOnly($refererUrl, $this->refererRouteByUrlTask::ROURE));
    }

    /**
     * @return list<list<string>>
     */
    public function badRefererUrlProvider(): array
    {
        return [
            ['http://localhost'],
            ['http://localhost/not-exists-page'],
            ['error url'],
        ];
    }
}
