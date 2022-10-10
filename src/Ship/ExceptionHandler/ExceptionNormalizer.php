<?php

declare(strict_types=1);

namespace App\Ship\ExceptionHandler;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @codeCoverageIgnore
 */
class ExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param FlattenException $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'code' => $object->getStatusCode(),
            'message' => $object->getMessage(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }
}
