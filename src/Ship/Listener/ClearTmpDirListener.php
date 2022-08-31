<?php

namespace App\Ship\Listener;

use App\Container\Profile\Task\ResizeAvatarTask;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ClearTmpDirListener implements EventSubscriberInterface
{
    private const PREFIXES = [
        ResizeAvatarTask::TMP_PREFIX
    ];

    public static function getSubscribedEvents()
    {
        return [KernelEvents::TERMINATE => 'onKernelTerminate'];
    }

    public function onKernelTerminate(): void
    {
        $pattern = sprintf('%s/{%s}*', sys_get_temp_dir(), implode(',', self::PREFIXES));

        array_map('unlink', glob($pattern, GLOB_NOSORT | GLOB_BRACE));
    }
}