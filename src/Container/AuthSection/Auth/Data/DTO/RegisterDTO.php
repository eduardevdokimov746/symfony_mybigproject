<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Data\DTO;

use App\Container\User\Data\DTO\Trait\UserDTOTrait;
use App\Ship\Parent\DTO;

class RegisterDTO extends DTO
{
    use UserDTOTrait;
}
