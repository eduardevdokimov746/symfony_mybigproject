<?php

declare(strict_types=1);

namespace App\Ship\Action;

use App\Ship\Parent\Action;
use Doctrine\ORM\Mapping\Entity;
use ReflectionClass;
use Symfony\Component\Form\FormInterface;

class ValidateFormAndSaveEntityAction extends Action
{
    public function run(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!is_object($data) || 0 === count((new ReflectionClass($data::class))->getAttributes(Entity::class))) {
                return false;
            }

            $this->persistAndFlush($data);

            return true;
        }

        return false;
    }
}
