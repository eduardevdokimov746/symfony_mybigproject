<?php

declare(strict_types=1);

namespace App\Container\Locale\Test\Integration\Task;

use App\Container\Locale\Task\SwitchTask;
use App\Ship\Parent\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class SwitchTaskTest extends KernelTestCase
{
    public function testRun(): void
    {
        $router = self::getContainer()->get(RouterInterface::class);
        $localeSwitcher = self::getContainer()->get(LocaleSwitcher::class);
        $enabledLocales = ['ru', 'en'];

        $switchTask = new SwitchTask($router, $localeSwitcher, $enabledLocales);

        $newLocale = $switchTask->run();

        $this->assertSame('en', $newLocale);
        $this->assertSame('en', $localeSwitcher->getLocale());
    }

    public function testRunExpectException(): void
    {
        $router = self::getContainer()->get(RouterInterface::class);
        $localeSwitcher = self::getContainer()->get(LocaleSwitcher::class);
        $enabledLocales = [];

        $this->expectExceptionMessage('empty');

        $switchTask = new SwitchTask($router, $localeSwitcher, $enabledLocales);
        $switchTask->run();
    }
}
