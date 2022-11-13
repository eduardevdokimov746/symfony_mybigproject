<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use App\Ship\Helper\File;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ClearTmpDirListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::TERMINATE => 'onKernelTerminate'];
    }

    public function onKernelTerminate(): void
    {
        File::removeTmpFiles();
    }
}
