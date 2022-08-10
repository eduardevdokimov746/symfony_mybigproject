<?php

namespace App\Ship\ExceptionHandler\Renderer;

use App\Ship\Contract\ExceptionRenderer;
use App\Ship\ExceptionHandler\ExceptionMapping;
use App\Ship\Transformer\ExceptionTransformer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class JsonExceptionRenderer implements ExceptionRenderer
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function render(ExceptionMapping $mapping): FlattenException
    {
        $asString = $this->serializer->serialize(
            new ExceptionTransformer($mapping->getCode(), $mapping->getMessage()),
            JsonEncoder::FORMAT
        );

        return FlattenException::createFromThrowable($mapping->getThrowable())
            ->setAsString($asString)
            ->setStatusCode($mapping->getCode())
            ->setStatusText($mapping->getMessage())
            ->setHeaders([
                'content-type' => 'application/json'
            ]);
    }
}