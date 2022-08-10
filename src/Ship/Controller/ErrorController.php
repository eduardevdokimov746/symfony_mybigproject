<?php

namespace App\Ship\Controller;

use App\Ship\ExceptionHandler\ExceptionMappingResolver;
use App\Ship\ExceptionHandler\Renderer\HtmlExceptionRenderer;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends Controller
{
    public function __construct(
        private ExceptionMappingResolver $mappingResolver,
        private HtmlExceptionRenderer $exceptionRenderer
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $exceptionMapping = $this->mappingResolver->resolve($request->attributes->get('exception'));

        $flattenException = $this->exceptionRenderer->render($exceptionMapping);

        return new Response($flattenException->getAsString(), $flattenException->getStatusCode(), $flattenException->getHeaders());
    }
}