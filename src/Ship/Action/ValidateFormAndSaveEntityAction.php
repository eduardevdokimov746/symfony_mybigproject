<?php

declare(strict_types=1);

namespace App\Ship\Action;

use App\Ship\Parent\Action;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use ReflectionClass;
use Symfony\Component\Form\FormInterface;

class ValidateFormAndSaveEntityAction extends Action
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function run(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!is_object($data) || empty((new ReflectionClass($data::class))->getAttributes(Entity::class))) {
                return false;
            }

            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
