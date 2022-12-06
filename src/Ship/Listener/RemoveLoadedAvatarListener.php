<?php

declare(strict_types=1);

namespace App\Ship\Listener;

use App\Container\User\Entity\Doc\User;
use App\Ship\Event\Form\ErrorSavingFormEvent;
use App\Ship\Service\ImageStorage\ImageStorage;
use App\Ship\Service\ImageStorage\ImageStorageEnum;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemoveLoadedAvatarListener implements EventSubscriberInterface
{
    public function __construct(
        private ImageStorage $imageStorage
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [ErrorSavingFormEvent::NAME => 'handle'];
    }

    public function handle(ErrorSavingFormEvent $event): void
    {
        $data = $event->getForm()->getData();

        if ($data instanceof User && !$data->getProfile()->isDefaultAvatar()) {
            $this->imageStorage->remove($data->getProfile()->getAvatar(), ImageStorageEnum::Avatar);
        }
    }
}
