<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\ExceptionHandler;

use App\Ship\ExceptionHandler\ExceptionMapping;
use App\Ship\ExceptionHandler\ExceptionMappingResolver;
use App\Ship\Parent\Test\TestCase;
use Exception;
use InvalidArgumentException;

class ExceptionMappingResolverTest extends TestCase
{
    public function mappingsProvider(): array
    {
        return [
            ['empty' => []],
            ['with defaults parameters' => ['class' => ['code' => 404]]],
            ['all parameters' => ['class' => ['code' => 404, 'loggable' => true, 'hidden' => true]]],
        ];
    }

    /**
     * @dataProvider mappingsProvider
     */
    public function testConstruct(array $mappings): void
    {
        $this->assertInstanceOf(ExceptionMappingResolver::class, new ExceptionMappingResolver($mappings));
    }

    public function testConstructExpectException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ExceptionMappingResolver([['class' => []]]);
    }

    public function testResolve(): void
    {
        $exceptionMappingResolver = new ExceptionMappingResolver([]);

        $this->assertInstanceOf(ExceptionMapping::class, $exceptionMappingResolver->resolve(new Exception()));
    }
}
