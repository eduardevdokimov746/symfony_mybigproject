<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\ImageResize;

use App\Ship\Parent\Test\TestCase;
use App\Ship\Service\ImageResize\NewsImageResizeService;
use App\Ship\Test\Unit\Service\ImageResize\Trait\AbstractImageResizeServiceTestTrait;

class NewsImageResizeServiceTest extends TestCase
{
    use AbstractImageResizeServiceTestTrait;

    /** @var class-string<NewsImageResizeService> */
    protected string $imageResizeService = NewsImageResizeService::class;
}
