<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\ExceptionHandler;

use App\Ship\ExceptionHandler\ExceptionMapping;
use App\Ship\Parent\Test\TestCase;
use Exception;
use Throwable;

class ExceptionMappingTest extends TestCase
{
    private ExceptionMapping $exceptionMapping;

    protected function setUp(): void
    {
        $this->exceptionMapping = new ExceptionMapping(404, true, true);
    }

    public function testFromCode(): void
    {
        $this->assertInstanceOf(ExceptionMapping::class, ExceptionMapping::fromCode(404));
    }

    public function testIsHidden(): void
    {
        $this->assertTrue($this->exceptionMapping->isHidden());
    }

    public function testIsLoggable(): void
    {
        $this->assertTrue($this->exceptionMapping->isLoggable());
    }

    public function testGetCode(): void
    {
        $this->assertIsInt($this->exceptionMapping->getCode());
    }

    public function testSetThrowable(): void
    {
        $this->assertInstanceOf(ExceptionMapping::class, $this->exceptionMapping->setThrowable(new Exception()));
    }

    public function testGetThrowable(): void
    {
        $this->assertInstanceOf(Throwable::class, $this->exceptionMapping->getThrowable());
    }

    public function testGetMessage(): void
    {
        $this->assertIsString($this->exceptionMapping->getMessage());
    }
}
