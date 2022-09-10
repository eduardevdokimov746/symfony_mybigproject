<?php

declare(strict_types=1);

namespace App\Ship\Contract;

interface Validator
{
    public function validate(array $data): array;

    public function isValid(): bool;

    public function getErrors(): array;

    public function getValidated(): array;
}
