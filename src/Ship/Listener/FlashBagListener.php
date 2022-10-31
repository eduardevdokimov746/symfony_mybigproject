<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use App\Ship\Enum\FlashBagNameEnum;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FlashBagListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['setFields', 1],
            KernelEvents::RESPONSE => 'clearFlashBag',
        ];
    }

    public function setFields(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $flashBag = $event->getRequest()->getSession()->getFlashBag();

            foreach ($event->getRequest()->request->all() as $name => $value) {
                if ($value instanceof UploadedFile) {
                    continue;
                }

                $flashBag->set(FlashBagNameEnum::FIELD->getNameFor($name), $value);
            }
        }
    }

    public function clearFlashBag(ResponseEvent $event): void
    {
        $flashBag = $event->getRequest()->getSession()->getFlashBag();
        $prefixes = array_map(fn (FlashBagNameEnum $enum) => $enum->value, FlashBagNameEnum::cases());
        $pattern = sprintf('#^[%s].*#', implode(',', $prefixes));

        foreach ($flashBag->peekAll() as $name => $value) {
            if (preg_match($pattern, $name)) {
                $flashBag->get($name);
            }
        }
    }
}
