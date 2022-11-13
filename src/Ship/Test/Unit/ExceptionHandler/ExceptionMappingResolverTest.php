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
    /**
     * @return list<array<string, array<string, array<string, bool|int>>>>
     */
    public function mappingsProvider(): array
    {
        return [
            ['empty' => []],
            ['with defaults parameters' => ['class' => ['code' => 404]]],
            ['all parameters' => ['class' => ['code' => 404, 'loggable' => true, 'hidden' => true]]],
        ];
    }

    /**
     * @param array<string, array{code: int, hidden?: bool, loggable?: bool}> $mappings
     *
     * @dataProvider mappingsProvider
     */
    public function testConstruct(array $mappings): void
    {
        self::assertInstanceOf(ExceptionMappingResolver::class, new ExceptionMappingResolver($mappings));
    }

    public function testConstructExpectException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ExceptionMappingResolver(['class' => []]);
    }

    public function testResolve(): void
    {
        $exceptionMappingResolver = new ExceptionMappingResolver([]);

        self::assertInstanceOf(ExceptionMapping::class, $exceptionMappingResolver->resolve(new Exception()));
    }
}
