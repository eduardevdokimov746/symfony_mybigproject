<?php

namespace App\Ship\Parent\Validator;

use App\Ship\Form\Extension\UseFlashBagExtension;
use App\Ship\Parent\Validator;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

abstract class FormTypeValidator extends Validator
{
    private FormInterface $form;

    public function __construct(
        private FormFactoryInterface $formFactory
    )
    {
    }

    public function validate(array $data): array
    {
        $this->start = true;
        $this->data = $data;

        if (!$this->getForm()->isSubmitted())
            $this->getForm()->submit($data);
        else
            $this->getForm(true)->submit($data);

        /** @var Form $child */
        foreach ($this->getForm() as $child) {
            $propertyName = $child->getName();

            if ($child->getErrors()->count() > 0) {
                $this->errors[$propertyName] = $child->getErrors()->current()->getMessage();
                $this->valid = false;
            } else {
                unset($this->errors[$propertyName]);
            }
        }

        if (empty($this->errors)) $this->valid = true;

        return $this->errors;
    }

    final public function getForm(bool $clearOld = false, bool $useFlashBag = UseFlashBagExtension::OPTION_DEFAULT_VALUE): FormInterface
    {
        if (!isset($this->form) || $clearOld)
            $this->form = $this->formFactory->create($this->getFormType(), options: [
                UseFlashBagExtension::OPTION_NAME => $useFlashBag
            ]);

        return $this->form;
    }

    public function getFormType(): string
    {
        return FormType::class;
    }
}