<?php

namespace App\Ship\Listener;

use App\Ship\ExceptionHandler\ExceptionMapping;
use App\Ship\ExceptionHandler\ExceptionMappingResolver;
use App\Ship\ExceptionHandler\ExceptionRendererFactory;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\RequestContext;
use Throwable;

class ExceptionListener
{
    private ?string $format;

    public function __construct(
        private ExceptionMappingResolver $mappingResolver,
        private LoggerInterface          $logger,
        private string                   $env,
        private ExceptionRendererFactory $exceptionRendererFactory,
        private ErrorRendererInterface   $twigErrorRenderer,
        private RequestContext           $requestContext
    )
    {
        $this->format = $requestContext->getParameter('format') ?? ExceptionRendererFactory::HTML_FORMAT;
    }

    public function __invoke(ExceptionEvent $event): void
    {
        if (strpos($this->requestContext->getPathInfo(), '_error')) return;

        $exceptionMapping = $this->resolveThrowable($event->getThrowable());

        if ($this->isShowDebugErrorPage()) {
            $flattenException = $this->twigErrorRenderer->render($exceptionMapping->getThrowable());

            $flattenException->setStatusCode($exceptionMapping->getCode());
        } else
            $flattenException = $this->createCustomResponse($exceptionMapping);

        if ($this->isShouldLog($exceptionMapping))
            $this->log($exceptionMapping);

        $event->setResponse(new Response(
            $flattenException->getAsString(),
            $flattenException->getStatusCode(),
            $flattenException->getHeaders()
        ));
    }

    private function resolveThrowable(Throwable $throwable): ExceptionMapping
    {
        return $this->mappingResolver->resolve($throwable);
    }

    private function isShowDebugErrorPage(): bool
    {
        return $this->env !== 'prod' && $this->format === 'html';
    }

    private function createCustomResponse(ExceptionMapping $exceptionMapping): FlattenException
    {
        return $this->exceptionRendererFactory->build($this->format)->render($exceptionMapping);
    }

    private function isShouldLog(ExceptionMapping $exceptionMapping): bool
    {
        return $exceptionMapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $exceptionMapping->isLoggable();
    }

    private function log(ExceptionMapping $exceptionMapping): void
    {
        $code = $exceptionMapping->getCode();
        $trace = $exceptionMapping->getThrowable()->getTrace();
        $fileException = array_shift($trace);

        $context = [
            'file'     => $exceptionMapping->getThrowable()->getFile(),
            'line'     => $exceptionMapping->getThrowable()->getLine(),
            'class'    => $fileException['class'],
            'function' => $fileException['function']
        ];

        switch (true) {
            case ($code >= Level::Error->value && $code < Level::Critical->value):
                $this->logger->error($exceptionMapping->getMessage(), $context);
                break;
            case ($code >= Level::Critical->value):
                $this->logger->critical($exceptionMapping->getMessage(), $context);
                break;
        }
    }
}