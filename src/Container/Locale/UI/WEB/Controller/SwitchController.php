<?php

declare(strict_types=1);

namespace App\Container\Locale\UI\WEB\Controller;

use App\Container\Locale\Action\SwitchAction;
use App\Ship\Parent\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/switch',
    name: 'switch',
    methods: ['GET']
)]
class SwitchController extends Controller
{
    public function __construct(
        private SwitchAction $switchAction
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @phpstan-ignore-next-line */
        return $this->switchAction->run($request->server->get('HTTP_REFERER'));
    }
}
