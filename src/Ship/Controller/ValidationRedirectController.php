<?php

namespace App\Ship\Controller;

use App\Ship\Parent\Controller;
use App\Ship\Task\GetRefererRouteByUrlTask;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ValidationRedirectController extends Controller
{
    public function __construct(
        private GetRefererRouteByUrlTask $refererRouteByUrlTask,
        private UrlGeneratorInterface    $urlGenerator,
        private RequestStack             $requestStack
    )
    {
    }

    public function __invoke(): Response
    {
        $route = $this->refererRouteByUrlTask->getOnly(
            $this->requestStack->getCurrentRequest()->server->get('HTTP_REFERER'),
            GetRefererRouteByUrlTask::ROURE
        );

        return $this->redirect($this->urlGenerator->generate($route));
    }
}