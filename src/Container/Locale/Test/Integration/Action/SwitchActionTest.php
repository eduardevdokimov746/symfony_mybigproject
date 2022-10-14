<?php

declare(strict_types=1);

namespace App\Container\Locale\Test\Integration\Action;

use App\Container\Locale\Action\SwitchAction;
use App\Container\Locale\Task\SwitchTask;
use App\Ship\Parent\Test\KernelTestCase;
use App\Ship\Task\GetRefererRouteByUrlTask;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SwitchActionTest extends KernelTestCase
{
    public function testRun(): void
    {
        $getRefererRouteUrlTask = self::getContainer()->get(GetRefererRouteByUrlTask::class);
        $switchTask = self::getContainer()->get(SwitchTask::class);
        $urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);

        $switchAction = new SwitchAction(
            $getRefererRouteUrlTask,
            $switchTask,
            'cookieName',
            '+1 minutes',
            $urlGenerator
        );

        $result = $switchAction->run('http://localhost/login');

        $this->assertInstanceOf(Response::class, $result);
    }
}
