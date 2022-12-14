<?php

declare(strict_types=1);

namespace App\Container\Locale\Test\Unit\Task;

use App\Container\Locale\Task\SwitchTask;
use App\Ship\Parent\Test\TestCase;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class SwitchTaskTest extends TestCase
{
    public function testRun(): void
    {
        $requestContext = self::createStub(RequestContext::class);
        $requestContext->method('getParameter')->willReturn('ru');
        $router = self::createStub(RouterInterface::class);
        $router->method('getContext')->willReturn($requestContext);
        $localeSwitcher = self::createStub(LocaleSwitcher::class);
        $enabledLocales = ['ru', 'en'];

        $switchTask = new SwitchTask($router, $localeSwitcher, $enabledLocales);

        $newLocale = $switchTask->run();

        self::assertSame('en', $newLocale);
    }

    public function testRunExpectException(): void
    {
        $requestContext = self::createStub(RequestContext::class);
        $requestContext->method('getParameter')->willReturn('ru');
        $router = self::createStub(RouterInterface::class);
        $router->method('getContext')->willReturn($requestContext);
        $localeSwitcher = self::createStub(LocaleSwitcher::class);
        $enabledLocales = [];

        $this->expectExceptionMessage('empty');

        $switchTask = new SwitchTask($router, $localeSwitcher, $enabledLocales);
        $switchTask->run();
    }
}
