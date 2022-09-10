<?php

declare(strict_types=1);

namespace App\Container\News\Exception;

use RuntimeException;

class NewsNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('News not found');
    }
}
