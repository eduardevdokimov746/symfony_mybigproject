<?php

declare(strict_types=1);

namespace App\Ship\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueConstraint extends Constraint
{
    public string $message = 'This value is already used';

    public string $entity;

    public ?string $property;

    #[HasNamedArguments]
    public function __construct(
        string $entity,
        string $property = null,
        string $message = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);

        $this->entity = $entity;
        $this->property = $property;
        $this->message = $message ?? $this->message;
    }
}
