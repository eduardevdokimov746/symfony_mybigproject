<?php

namespace App\Ship\Listener;

use App\Ship\ExceptionHandler\ExceptionMappingResolver;
use App\Ship\ExceptionHandler\ExceptionRendererFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\RequestContext;

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

        $throwable = $event->getThrowable();

        if ($this->isShowDebugErrorPage())
            $flattenException = $this->twigErrorRenderer->render($throwable);
        else
            $flattenException = $this->createCustomResponse($throwable);

//        if ($exceptionMapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $exceptionMapping->isLoggable())

        $event->setResponse(new Response(
            $flattenException->getAsString(),
            $flattenException->getStatusCode(),
            $flattenException->getHeaders()
        ));
    }

    private function isShowDebugErrorPage(): bool
    {
        return $this->env !== 'prod' && $this->format === 'html';
    }

    private function createCustomResponse(\Throwable $throwable): FlattenException
    {
        $exceptionResolver = $this->mappingResolver->resolve($throwable);

        return $this->exceptionRendererFactory->build($this->format)->render($exceptionResolver);
    }
}