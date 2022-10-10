<?php

declare(strict_types=1);

namespace App\Ship\Test\Integration\Task;

use App\Ship\Task\GetRefererRouteByUrlTask;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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
        $this->assertNull($this->refererRouteByUrlTask->run($refererUrl));
    }

    /**
     * @dataProvider badRefererUrlProvider
     */
    public function testGetOnlyExpectNull(string $refererUrl): void
    {
        $this->assertNull($this->refererRouteByUrlTask->getOnly($refererUrl, $this->refererRouteByUrlTask::ROURE));
    }

    public function badRefererUrlProvider(): array
    {
        return [
            ['http://localhost'],
            ['http://localhost/not-exists-page'],
            ['error url'],
        ];
    }
}
