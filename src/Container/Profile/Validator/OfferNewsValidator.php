<?php

declare(strict_types=1);

namespace App\Container\Profile\Validator;

use App\Ship\Parent\Validator\PropertyValidator;
use App\Ship\Service\ImageResize\NewsImageResizeService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class OfferNewsValidator extends PropertyValidator
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    #[Assert\Type('string')]
    private string $title;

    #[Assert\NotBlank]
    #[Assert\Length(min: 255)]
    #[Assert\Type('string')]
    private string $content;

    #[Assert\Image(
        minWidth: NewsImageResizeService::WIDTH,
        minHeight: NewsImageResizeService::HEIGHT,
        maxSize: '5M',
        mimeTypes: ['image/png', 'image/jpeg', 'image/gif'],
        mimeTypesMessage: 'This file is not a valid image.'
    )]
    private UploadedFile $image;
}