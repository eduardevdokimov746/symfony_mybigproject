<?php

declare(strict_types=1);

namespace App\Container\Profile\Data\DTO;

use App\Ship\Parent\DTO;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateProfileFromAuthUserDTO extends DTO
{
    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $patronymic = null;

    public ?string $about = null;

    public ?UploadedFile $avatar = null;

    public bool $deleteAvatar = false;
}
