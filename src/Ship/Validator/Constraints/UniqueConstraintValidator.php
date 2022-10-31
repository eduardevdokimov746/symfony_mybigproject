<?php

declare(strict_types=1);

namespace App\Ship\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueConstraint::class);
        }

        $entity = $constraint->entity;
        $property = $constraint->property ?? $this->context->getPropertyName();

        if (!class_exists($entity)) {
            throw new InvalidOptionsException('Entity {'.$entity.'} does not exists', [$entity]);
        }

        if (!property_exists($entity, $property)) {
            throw new InvalidOptionsException(
                'Property {'.$property.'} does not exists in entity {'.$entity.'}',
                [$entity, $property]
            );
        }

        $repository = $this->entityManager->getRepository($entity);

        if (null !== $repository->findOneBy([$property => $value])) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
