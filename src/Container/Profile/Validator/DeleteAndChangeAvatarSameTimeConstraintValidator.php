<?php

declare(strict_types=1);

namespace App\Container\Profile\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DeleteAndChangeAvatarSameTimeConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof DeleteAndChangeAvatarSameTimeConstraint) {
            throw new UnexpectedTypeException($constraint, DeleteAndChangeAvatarSameTimeConstraint::class);
        }

        if (!is_bool($value)) {
            throw new UnexpectedTypeException($value, 'bool');
        }

        if (!property_exists($this->context->getObject(), $constraint->avatarProperty)) {
            throw new InvalidOptionsException('Property {'.$constraint->avatarProperty.'} does not exists', ['avatarProperty']);
        }

        if (
            $this->context->getObject()->{$constraint->avatarProperty} instanceof UploadedFile
            && true === $value
        ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
