<?php

declare(strict_types=1);

namespace App\Ship\Helper;

use App\Ship\Contract\ImageResize;
use Exception;

class RemoveFilesFromTmpDir
{
    private const COMMON_PREFIXES = [
        ImageResize::TMP_PREFIX,
    ];

    public static function run(array|string $prefix = []): bool
    {
        try {
            $prefix = is_string($prefix) ? [$prefix] : $prefix;

            $prefixes = implode(',', array_merge(self::COMMON_PREFIXES, $prefix));

            $pattern = sprintf('%s/{%s}*', sys_get_temp_dir(), $prefixes);

            array_map('unlink', glob($pattern, GLOB_NOSORT | GLOB_BRACE));

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
