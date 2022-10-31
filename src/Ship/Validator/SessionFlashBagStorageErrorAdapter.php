<?php

declare(strict_types=1);

namespace App\Ship\Validator;

use App\Ship\Contract\StorageErrorAdapter;
use App\Ship\Enum\FlashBagNameEnum;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SessionFlashBagStorageErrorAdapter implements StorageErrorAdapter
{
    private FlashBagInterface $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getCurrentRequest()->getSession()->getFlashBag();
    }

    public function save(ConstraintViolationListInterface $list): void
    {
        $messagesGroupedByPropertyPath = [];

        foreach ($list as $constraintViolation) {
            $messagesGroupedByPropertyPath[$constraintViolation->getPropertyPath()][] = $constraintViolation->getMessage();
        }

        foreach ($messagesGroupedByPropertyPath as $propertyPath => $messages) {
            $this->flashBag->get(FlashBagNameEnum::ERROR->getNameFor($propertyPath));

            array_map(
                fn (string $message) => $this->flashBag->add(FlashBagNameEnum::ERROR->getNameFor($propertyPath), $message),
                $messages
            );
        }
    }
}
