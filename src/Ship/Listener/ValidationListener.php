<?php

namespace App\Ship\Listener;

use App\Ship\Contract\Validator;
use App\Ship\Controller\ValidationRedirectController;
use App\Ship\Parent\Validator\FormTypeValidator;
use App\Ship\Task\GetFlashBagNameTask;
use LogicException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ValidationListener implements EventSubscriberInterface
{
    public const CHECK_METHODS = [
        'POST',
        'PATCH',
        'PUT',
        'DELETE'
    ];

    public function __construct(
        private ValidationRedirectController $validationController,
        private GetFlashBagNameTask          $getErrorNameTask
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments'
        ];
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        $request = $event->getRequest();

        if (is_null($validator = $this->findValidator($event->getArguments()))) return;

        if (
            $this->isSubmitted($validator, $request) &&
            count($errors = $validator->validate($this->getValidateData($validator, $request))) > 0
        ) {
            $this->saveErrorsAndFieldInFlashBag(
                $errors,
                $this->getValidateData($validator, $request),
                $request->getSession()->getFlashBag()
            );

            $event->setController($this->validationController);
        }
    }

    private function findValidator(array $arguments): FormTypeValidator|Validator|null
    {
        $validators = array_filter($arguments, fn($item) => $item instanceof Validator);

        if (count($validators) > 1) throw new LogicException('Too many inject validators');

        return array_pop($validators);
    }

    private function isSubmitted(Validator $validator, Request $request): bool
    {
        if ($validator instanceof FormTypeValidator)
            return $validator->getForm(useFlashBag: false)->handleRequest($request)->isSubmitted();

        return in_array($request->getMethod(), self::CHECK_METHODS);
    }

    private function getValidateData(Validator $validator, Request $request): array
    {
        if ($validator instanceof FormTypeValidator)
            return $request->request->all()[$validator->getForm()->getName()];

        return $request->request->all();
    }

    private function saveErrorsAndFieldInFlashBag(array $errors, array $fields, FlashBagInterface $flashBag): void
    {
        foreach ($errors as $param => $message) {
            $flashBag->get($this->getErrorNameTask->forError($param));
            $flashBag->add($this->getErrorNameTask->forError($param), $message);
        }

        foreach ($fields as $name => $value) {
            $flashBag->get($this->getErrorNameTask->forField($name));
            $flashBag->add($this->getErrorNameTask->forField($name), $value);
        }
    }
}