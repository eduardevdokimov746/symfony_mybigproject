<?php

namespace App\Ship\ExceptionHandler;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param FlattenException $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        return [
            'code'    => $object->getStatusCode(),
            'message' => $object->getMessage()
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof FlattenException;
    }
}