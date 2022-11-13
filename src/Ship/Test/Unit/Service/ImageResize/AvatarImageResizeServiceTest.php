<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\ImageResize;

use App\Ship\Parent\Test\TestCase;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use App\Ship\Test\Unit\Service\ImageResize\Trait\AbstractImageResizeServiceTestTrait;

class AvatarImageResizeServiceTest extends TestCase
{
    use AbstractImageResizeServiceTestTrait;

    /** @var class-string<AvatarImageResizeService> */
    protected string $imageResizeService = AvatarImageResizeService::class;
}
