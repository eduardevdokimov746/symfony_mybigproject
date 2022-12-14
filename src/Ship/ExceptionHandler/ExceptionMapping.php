<?php

declare(strict_types=1);

namespace App\Ship\ExceptionHandler;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ExceptionMapping
{
    public const HIDDEN = true;
    public const LOGGABLE = false;

    private Throwable $throwable;

    public function __construct(private int $code, private bool $hidden, private bool $loggable)
    {
        $this->throwable = new RuntimeException('Default exception');
    }

    public static function fromCode(int $code): self
    {
        return new self($code, self::HIDDEN, self::LOGGABLE);
    }

    public function getMessage(): string
    {
        if ($this->isHidden()) {
            return Response::$statusTexts[$this->getCode()];
        }

        $message = $this->getThrowable()->getMessage();

        return '' === $message ? $message : Response::$statusTexts[$this->getCode()];
    }

    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }

    public function setThrowable(Throwable $throwable): self
    {
        $this->throwable = $throwable;

        return $this;
    }

    public function getCode(): int
    {
        if ($this->getThrowable() instanceof HttpExceptionInterface) {
            return $this->getThrowable()->getStatusCode();
        }

        return $this->code;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function isLoggable(): bool
    {
        return $this->loggable;
    }
}
