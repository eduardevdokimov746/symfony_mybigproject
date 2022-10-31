<?php

declare(strict_types=1);

namespace App\Ship\Contract;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface StorageErrorAdapter
{
    public function save(ConstraintViolationListInterface $list): void;
}
