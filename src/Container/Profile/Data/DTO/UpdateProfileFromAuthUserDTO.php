<?php

declare(strict_types=1);

namespace App\Container\Profile\Data\DTO;

use App\Container\Profile\Validator\DeleteAndChangeAvatarSameTimeConstraint;
use App\Ship\Attribute\DefaultValue;
use App\Ship\Parent\DTO;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateProfileFromAuthUserDTO extends DTO
{
    #[Assert\Length(max: 255)]
    #[Assert\Regex('#[\d\s]+#', match: false, message: 'only_alpha')]
    #[DefaultValue(null)]
    public readonly ?string $firstName;

    #[Assert\Length(max: 255)]
    #[Assert\Regex('#[\d\s]+#', match: false, message: 'only_alpha')]
    #[DefaultValue(null)]
    public readonly ?string $lastName;

    #[Assert\Length(max: 255)]
    #[Assert\Regex('#[\d\s]+#', match: false, message: 'only_alpha')]
    #[DefaultValue(null)]
    public readonly ?string $patronymic;

    #[Assert\Type('string')]
    #[DefaultValue(null)]
    public readonly ?string $about;

    #[Assert\Image(
        minWidth: AvatarImageResizeService::WIDTH,
        minHeight: AvatarImageResizeService::HEIGHT,
        maxSize: '5M',
        mimeTypes: ['image/png', 'image/jpeg', 'image/gif'],
        mimeTypesMessage: 'This file is not a valid image.'
    )]
    #[DefaultValue(value: null)]
    public readonly ?UploadedFile $avatar;

    #[Assert\Type('bool')]
    #[DeleteAndChangeAvatarSameTimeConstraint(avatarProperty: 'avatar')]
    #[DefaultValue(false)]
    public readonly bool $deleteAvatar;
}
