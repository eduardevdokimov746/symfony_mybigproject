<?php

namespace App\Ship\ExceptionHandler;

use App\Ship\Contract\ExceptionRenderer;
use App\Ship\ExceptionHandler\Renderer\HtmlExceptionRenderer;
use App\Ship\ExceptionHandler\Renderer\JsonExceptionRenderer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ExceptionRendererFactory implements ServiceSubscriberInterface
{
    public const HTML_FORMAT = 'html';
    public const JSON_FORMAT = 'json';

    public function __construct(private ContainerInterface $container)
    {
    }

    public static function getSubscribedServices(): array
    {
        return [
            HtmlExceptionRenderer::class,
            JsonExceptionRenderer::class
        ];
    }

    public function build(string $format = self::HTML_FORMAT): ExceptionRenderer
    {
        return match ($format) {
            self::HTML_FORMAT => $this->container->get(HtmlExceptionRenderer::class),
            self::JSON_FORMAT => $this->container->get(JsonExceptionRenderer::class)
        };
    }
}