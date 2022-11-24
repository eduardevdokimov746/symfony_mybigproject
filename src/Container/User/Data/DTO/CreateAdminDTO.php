<?php

declare(strict_types=1);

namespace App\Container\User\Data\DTO;

use App\Container\User\Data\DTO\Trait\UserDTOTrait;
use App\Ship\Parent\DTO;

class CreateAdminDTO extends DTO
{
    use UserDTOTrait;
}
