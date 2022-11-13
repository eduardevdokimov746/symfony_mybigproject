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
        self::assertInstanceOf(ExceptionMapping::class, ExceptionMapping::fromCode(404));
    }

    public function testIsHidden(): void
    {
        self::assertTrue($this->exceptionMapping->isHidden());
    }

    public function testIsLoggable(): void
    {
        self::assertTrue($this->exceptionMapping->isLoggable());
    }

    public function testGetCode(): void
    {
        self::assertIsInt($this->exceptionMapping->getCode());
    }

    public function testSetThrowable(): void
    {
        self::assertInstanceOf(ExceptionMapping::class, $this->exceptionMapping->setThrowable(new Exception()));
    }

    public function testGetThrowable(): void
    {
        self::assertInstanceOf(Throwable::class, $this->exceptionMapping->getThrowable());
    }

    public function testGetMessage(): void
    {
        self::assertIsString($this->exceptionMapping->getMessage());
    }
}
