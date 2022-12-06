<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\Listener;

use App\Container\Profile\Task\ChangeAvatarTask;
use App\Container\User\Entity\Doc\User;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarListener implements EventSubscriberInterface
{
    private FormInterface $form;
    private User $user;
    private ?UploadedFile $file;
    private bool $isDeleteAvatar;

    public function __construct(
        protected ChangeAvatarTask $changeAvatarTask
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::POST_SUBMIT => 'onPostSubmit'];
    }

    public function onPostSubmit(PostSubmitEvent $event): void
    {
        $this->init($event);

        if (!$this->form->isValid()) {
            return;
        }

        if ($this->shouldChangeAvatar()) {
            /** @phpstan-ignore-next-line */
            $newAvatar = $this->isDeleteAvatar ? null : AvatarImageResizeService::createFromUploadedFile($this->file)->run();

            $this->changeAvatarTask->lazy()->run($this->user->getProfile(), $newAvatar);
        }
    }

    private function init(PostSubmitEvent $event): void
    {
        $this->form = $event->getForm();

        /** @phpstan-ignore-next-line  */
        $this->file = $this->form->get('avatar')->getData();
        $this->isDeleteAvatar = $this->form->has('delete_avatar') && true === $this->form->get('delete_avatar')->getData();

        /** @phpstan-ignore-next-line  */
        $this->user = $event->getData();
    }

    private function shouldChangeAvatar(): bool
    {
        return $this->file instanceof UploadedFile || $this->isDeleteAvatar;
    }
}
