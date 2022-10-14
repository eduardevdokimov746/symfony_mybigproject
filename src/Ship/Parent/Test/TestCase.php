<?php

declare(strict_types=1);

namespace App\Ship\Parent\Test;

use App\Ship\Trait\CreateTestUserInstanceTrait;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    use CreateTestUserInstanceTrait;
}
