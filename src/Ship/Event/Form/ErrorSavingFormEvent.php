<?php

declare(strict_types=1);

namespace App\Ship\Event\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ErrorSavingFormEvent extends Event
{
    public const NAME = 'form.error_saving';

    public function __construct(
        private FormInterface $form
    ) {
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
