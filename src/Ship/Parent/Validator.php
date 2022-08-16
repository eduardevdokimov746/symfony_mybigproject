<?php

namespace App\Ship\Parent;

use App\Ship\Contract\Validator as ValidatorInterface;

abstract class Validator implements ValidatorInterface
{
    protected array $errors = [];

    protected bool $valid = false;

    protected bool $start = false;

    protected array $data = [];

    public function getValidated(): array
    {
        if (!$this->start) return [];

        return array_diff_key($this->data, $this->getErrors());
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}