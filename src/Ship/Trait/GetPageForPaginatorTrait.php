<?php

declare(strict_types=1);

namespace App\Ship\Trait;

trait GetPageForPaginatorTrait
{
    private function getPage(string|int $page = null): int
    {
        if (is_null($page) || (is_string($page) && !ctype_digit($page))) {
            return 1;
        }

        return (int) $page;
    }
}
