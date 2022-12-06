<?php

declare(strict_types=1);

namespace App\Ship\Action;

use App\Ship\Event\Form\ErrorSavingFormEvent;
use App\Ship\Event\Form\SavedFormEvent;
use App\Ship\Parent\Action;
use Doctrine\ORM\Mapping\Entity;
use Exception;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ValidateFormAndSaveEntityAction extends Action
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger
    ) {
    }

    public function run(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!is_object($data) || 0 === count((new ReflectionClass($data::class))->getAttributes(Entity::class))) {
                return false;
            }

            try {
                $this->persistAndFlush($data);
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
                $form->addError(new FormError(''));
                $this->eventDispatcher->dispatch(new ErrorSavingFormEvent($form), ErrorSavingFormEvent::NAME);

                return false;
            }

            $this->eventDispatcher->dispatch(new SavedFormEvent($form), SavedFormEvent::NAME);

            return true;
        }

        return false;
    }
}
