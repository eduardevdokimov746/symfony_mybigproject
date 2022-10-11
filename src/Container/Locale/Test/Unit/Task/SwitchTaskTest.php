<?php

declare(strict_types=1);

namespace App\Container\Locale\Test\Unit\Task;

use App\Container\Locale\Task\SwitchTask;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class SwitchTaskTest extends TestCase
{
    public function testRun(): void
    {
        $requestContext = $this->createStub(RequestContext::class);
        $requestContext->method('getParameter')->willReturn('ru');
        $router = $this->createStub(RouterInterface::class);
        $router->method('getContext')->willReturn($requestContext);
        $localeSwitcher = $this->createStub(LocaleSwitcher::class);
        $enabledLocales = ['ru', 'en'];

        $switchTask = new SwitchTask($router, $localeSwitcher, $enabledLocales);

        $newLocale = $switchTask->run();

        $this->assertSame('en', $newLocale);
    }

    public function testRunExpectException(): void
    {
        $requestContext = $this->createStub(RequestContext::class);
        $requestContext->method('getParameter')->willReturn('ru');
        $router = $this->createStub(RouterInterface::class);
        $router->method('getContext')->willReturn($requestContext);
        $localeSwitcher = $this->createStub(LocaleSwitcher::class);
        $enabledLocales = [];

        $this->expectExceptionMessage('empty');

        $switchTask = new SwitchTask($router, $localeSwitcher, $enabledLocales);
        $switchTask->run();
    }
}
