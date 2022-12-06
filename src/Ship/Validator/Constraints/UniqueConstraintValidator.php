<?php

declare(strict_types=1);

namespace App\Ship\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
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

        if (!class_exists($entity)) {
            throw new InvalidOptionsException('Entity {'.$entity.'} does not exists', [$entity]);
        }

        $property = $this->findProperty($constraint);

        if (null === $property || !property_exists($entity, $property)) {
            throw new InvalidOptionsException(
                'Property {'.$property.'} does not exists in entity {'.$entity.'}',
                [$entity, $property]
            );
        }

        $repository = $this->entityManager->getRepository($entity);

        $qb = $repository->createQueryBuilder('q');

        foreach ($constraint->exceptCriteria as $propEntity => $exceptValue) {
            $qb
                ->orWhere("q.{$propEntity} != :{$propEntity}")
                ->setParameter($propEntity, $exceptValue)
            ;
        }

        $qb
            ->andWhere("q.{$property} = :{$property}")
            ->setParameter($property, $value)
            ->setMaxResults(1)
        ;

        if (null !== $qb->getQuery()->getOneOrNullResult()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }

    private function findProperty(Constraint $constraint): ?string
    {
        $property = $constraint->property ?? $this->context->getPropertyName();

        if (null === $property && $this->context->getObject() instanceof FormInterface) {
            $property = $this->context->getObject()->getName();
        }

        return $property;
    }
}
