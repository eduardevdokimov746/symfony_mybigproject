<?php

namespace App\Ship\Transformer;

use App\Ship\Parent\Transformer;

class ExceptionTransformer extends Transformer
{
    private int $code;
    private string $message;

    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}