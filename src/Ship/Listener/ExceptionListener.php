<?php

namespace App\Ship\Listener;

use App\Ship\ExceptionHandler\ExceptionMapping;
use App\Ship\ExceptionHandler\ExceptionMappingResolver;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
    public function __construct(
        private ExceptionMappingResolver $mappingResolver,
        private LoggerInterface          $logger
    )
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $exceptionMapping = $this->mappingResolver->resolve($throwable);

        $event->setThrowable(new HttpException(
            $exceptionMapping->getCode(),
            $exceptionMapping->getMessage(),
        ));

        if ($exceptionMapping->getCode() >= Response::HTTP_INTERNAL_SERVER_ERROR || $exceptionMapping->isLoggable())
            $this->log($exceptionMapping);
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