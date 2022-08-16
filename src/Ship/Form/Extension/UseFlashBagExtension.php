<?php

namespace App\Ship\Form\Extension;

use App\Ship\Task\GetFlashBagNameTask;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UseFlashBagExtension extends AbstractTypeExtension
{
    public const OPTION_NAME = 'use_flash_bag';
    public const OPTION_DEFAULT_VALUE = true;

    public function __construct(
        private RequestStack $requestStack,
        private GetFlashBagNameTask $getErrorNameTask
    )
    {
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'preSetData']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->define(self::OPTION_NAME)
            ->default(self::OPTION_DEFAULT_VALUE)
            ->allowedTypes('bool');

        $resolver
            ->define('use_flash_field')
            ->default(true)
            ->allowedTypes('bool');
    }

    public function preSetData(FormEvent $event): void
    {
        if (!$event->getForm()->getConfig()->getOption(self::OPTION_NAME))
            return;

        /** @var FormInterface $child */
        foreach ($event->getForm() as $child) {
            $errorName = $this->getErrorNameTask->forError($child->getName());

            if ($this->requestStack->getSession()->getFlashBag()->has($errorName))
                $child->addError(new FormError($this->requestStack->getSession()->getFlashBag()->get($errorName)[0]));

            $fieldName = $this->getErrorNameTask->forField($child->getName());

            if (
                $child->getConfig()->getOption('use_flash_field') &&
                $this->requestStack->getSession()->getFlashBag()->has($fieldName)
            )
                $child->setData($this->requestStack->getSession()->getFlashBag()->get($fieldName)[0]);
        }
    }
}