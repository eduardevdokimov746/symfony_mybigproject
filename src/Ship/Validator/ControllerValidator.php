<?php

declare(strict_types=1);

namespace App\Ship\Validator;

use App\Ship\Contract\StorageErrorAdapter;
use App\Ship\Parent\DTO;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ControllerValidator
{
    public function __construct(
        private StorageErrorAdapter $storageErrorAdapter,
        private ValidatorInterface $validator
    ) {
    }

    public function validate(DTO $dto): bool
    {
        $errors = $this->validator->validate($dto);

        $this->storageErrorAdapter->save($errors);

        return 0 === count($errors);
    }
}
