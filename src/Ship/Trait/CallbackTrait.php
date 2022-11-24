<?php

declare(strict_types=1);

namespace App\Ship\Trait;

trait CallbackTrait
{
    protected function call(?callable $callback, mixed ...$args): mixed
    {
        return is_null($callback) ? null : call_user_func($callback, ...$args);
    }
}
