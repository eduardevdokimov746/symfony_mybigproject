<?php

namespace App\Container\Profile\Validator;

use App\Container\Profile\Task\ResizeAvatarTask;
use App\Ship\Parent\Validator\PropertyValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EditProfileValidator extends PropertyValidator
{
    #[Assert\Length(max: 255)]
    #[Assert\Regex('#[\d\s]+#', match: false, message: 'only_alpha')]
    private ?string $firstName;

    #[Assert\Length(max: 255)]
    #[Assert\Regex('#[\d\s]+#', match: false, message: 'only_alpha')]
    private ?string $lastName;

    #[Assert\Length(max: 255)]
    #[Assert\Regex('#[\d\s]+#', match: false, message: 'only_alpha')]
    private ?string $patronymic;

    #[Assert\Type('string')]
    private ?string $about;

    #[Assert\Image(
        minWidth: ResizeAvatarTask::AVATAR_WIDTH,
        minHeight: ResizeAvatarTask::AVATAR_HEIGHT,
        maxSize: '5M',
        mimeTypes: ['image/png', 'image/jpeg', 'image/gif'],
        mimeTypesMessage: 'This file is not a valid image.'
    )]
    private ?UploadedFile $avatar;

    #[Assert\Type('bool')]
    private bool $deleteAvatar;

    #[Assert\IsFalse(message: 'change_delete_avatar')]
    public function isAvatar(): bool
    {
        if (!isset($this->avatar) || !isset($this->deleteAvatar)) return false;

        return $this->avatar instanceof UploadedFile && $this->deleteAvatar === true;
    }
}