<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\Doctrine;

use App\Ship\Service\Doctrine\CustomComparator;
use Doctrine\Migrations\Version\Version;
use PHPUnit\Framework\TestCase;

class CustomComparatorTest extends TestCase
{
    /**
     * @dataProvider compareData
     */
    public function testCompare(string $version1, string $version2, int $expected): void
    {
        $comparator = new CustomComparator();

        $this->assertSame($expected, $comparator->compare(new Version($version1), new Version($version2)));
    }

    public function compareData(): array
    {
        return [
            ['Version00000000000001.php', 'Version00000000000002.php', -1],
            ['Version00000000000001.php', 'Version00000000000001.php', 0],
            ['Version00000000000002.php', 'Version00000000000001.php', 1],
        ];
    }
}
