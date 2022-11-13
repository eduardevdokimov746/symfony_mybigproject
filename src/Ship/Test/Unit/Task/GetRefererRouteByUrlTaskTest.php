<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Task;

use App\Ship\Parent\Test\TestCase;
use App\Ship\Task\GetRefererRouteByUrlTask;
use RuntimeException;
use Symfony\Component\Routing\RouterInterface;

class GetRefererRouteByUrlTaskTest extends TestCase
{
    private GetRefererRouteByUrlTask $refererRouteByUrlTask;
    private string $refererUrl = 'http://localhost/login?login=user&password=123';

    protected function setUp(): void
    {
        $router = self::createStub(RouterInterface::class);
        $router->method('match')->willReturn([
            '_route' => 'app.home',
            '_controller' => 'controller::method',
        ]);

        $this->refererRouteByUrlTask = new GetRefererRouteByUrlTask($router);
    }

    public function testRunExpectArray(): void
    {
        $expect = [
            'route' => [
                '_route' => 'app.home',
                '_controller' => 'controller::method',
            ],
            'parameters' => [
                'login' => 'user',
                'password' => '123',
            ],
        ];

        self::assertSame($expect, $this->refererRouteByUrlTask->run($this->refererUrl));
    }

    public function testGetOnly(): void
    {
        self::assertSame('app.home', $this->refererRouteByUrlTask->getOnly($this->refererUrl, $this->refererRouteByUrlTask::ROURE));
        self::assertSame('controller::method', $this->refererRouteByUrlTask->getOnly($this->refererUrl, $this->refererRouteByUrlTask::CONTROLLER));
    }

    public function testGetOnlyExpectException(): void
    {
        $this->expectException(RuntimeException::class);

        $this->refererRouteByUrlTask->getOnly($this->refererUrl, 'error');
    }
}
