<?php

declare(strict_types=1);

namespace App\Container\Profile\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/',
    name: 'index',
    methods: 'GET'
)]
#[IsGranted('IS_AUTHENTICATED')]
class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return $this->render('@profile/index.html.twig');
    }
}
