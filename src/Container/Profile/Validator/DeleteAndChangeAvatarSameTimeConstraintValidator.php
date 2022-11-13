<?php

declare(strict_types=1);

namespace App\Container\Profile\Validator;

use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DeleteAndChangeAvatarSameTimeConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DeleteAndChangeAvatarSameTimeConstraint) {
            throw new UnexpectedTypeException($constraint, DeleteAndChangeAvatarSameTimeConstraint::class);
        }

        if (!is_bool($value)) {
            throw new UnexpectedTypeException($value, 'bool');
        }

        if (null === $object = $this->context->getObject()) {
            throw new RuntimeException('Object of context is null');
        }

        if (!property_exists($object, $constraint->avatarProperty)) {
            throw new InvalidOptionsException('Property {'.$constraint->avatarProperty.'} does not exists', ['avatarProperty']);
        }

        if (
            $object->{$constraint->avatarProperty} instanceof UploadedFile
            && true === $value
        ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
