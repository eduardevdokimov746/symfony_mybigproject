<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\ImageResize;

use App\Ship\Service\ImageResize\NewsImageResizeService;
use App\Ship\Test\Unit\Service\ImageResize\Trait\AbstractImageResizeServiceTestTrait;
use PHPUnit\Framework\TestCase;

class NewsImageResizeServiceTest extends TestCase
{
    use AbstractImageResizeServiceTestTrait;

    private string $imageResizeService = NewsImageResizeService::class;
}
