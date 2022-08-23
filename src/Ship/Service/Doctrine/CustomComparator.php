<?php

namespace App\Ship\Service\Doctrine;

use Doctrine\Migrations\Version\Comparator;
use Doctrine\Migrations\Version\Version;

class CustomComparator implements Comparator
{
    public function compare(Version $a, Version $b): int
    {
        return strcmp($this->getVersion($a), $this->getVersion($b));
    }

    private function getVersion(string $version): string
    {
        preg_match('#.+\\\\(?<version>\S+)$#', $version, $matches);

        return $matches['version'];
    }
}