<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Exception;

use RuntimeException;

class CategoryNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Category not found');
    }
}
