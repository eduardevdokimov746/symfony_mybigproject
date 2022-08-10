<?php

namespace App\Ship\ExceptionHandler\Renderer;

use App\Ship\Contract\ExceptionRenderer;
use App\Ship\ExceptionHandler\ExceptionMapping;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Twig\Environment;

class HtmlExceptionRenderer implements ExceptionRenderer
{
    public const TEMPLATE = '@ship/error/error.html.twig';

    public function __construct(private Environment $twig)
    {
    }

    public function render(ExceptionMapping $mapping): FlattenException
    {
        $asString = $this->twig->render(self::TEMPLATE, [
            'code'    => $mapping->getCode(),
            'message' => $mapping->getMessage()
        ]);

        return FlattenException::createFromThrowable($mapping->getThrowable())
            ->setAsString($asString)
            ->setStatusCode($mapping->getCode())
            ->setStatusText($mapping->getMessage());
    }
}