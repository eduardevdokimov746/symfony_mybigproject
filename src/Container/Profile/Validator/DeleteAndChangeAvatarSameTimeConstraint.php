<?php

declare(strict_types=1);

namespace App\Container\Profile\Validator;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class DeleteAndChangeAvatarSameTimeConstraint extends Constraint
{
    public string $message = 'change_delete_avatar';

    public string $avatarProperty;

    #[HasNamedArguments]
    public function __construct(
        string $avatarProperty,
        string $message = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);

        $this->avatarProperty = $avatarProperty;
        $this->message = $message ?? $this->message;
    }
}
