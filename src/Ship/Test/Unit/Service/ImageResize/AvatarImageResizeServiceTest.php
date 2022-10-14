<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\ImageResize;

use App\Ship\Service\ImageResize\AvatarImageResizeService;
use App\Ship\Test\Unit\Service\ImageResize\Trait\AbstractImageResizeServiceTestTrait;
use App\Ship\Parent\Test\TestCase;

class AvatarImageResizeServiceTest extends TestCase
{
    use AbstractImageResizeServiceTestTrait;

    private string $imageResizeService = AvatarImageResizeService::class;
}
