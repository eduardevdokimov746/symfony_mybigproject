<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Service\Doctrine;

use App\Ship\Parent\Test\TestCase;
use App\Ship\Service\Doctrine\CustomComparator;
use Doctrine\Migrations\Version\Version;

class CustomComparatorTest extends TestCase
{
    /**
     * @dataProvider compareData
     */
    public function testCompare(string $version1, string $version2, int $expected): void
    {
        $comparator = new CustomComparator();

        self::assertSame($expected, $comparator->compare(new Version($version1), new Version($version2)));
    }

    /**
     * @return non-empty-list<non-empty-list<int|string>>
     */
    public function compareData(): array
    {
        return [
            ['Version00000000000001.php', 'Version00000000000002.php', -1],
            ['Version00000000000001.php', 'Version00000000000001.php', 0],
            ['Version00000000000002.php', 'Version00000000000001.php', 1],
        ];
    }
}
