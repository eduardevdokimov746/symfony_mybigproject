<?php

namespace App\Container\Profile\UI\WEB\Controller;

use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

#[Route(
    path: '/',
    name: 'index',
    methods: ['GET']
)]
#[IsGranted('ROLE_USER')]
class IndexController extends Controller
{
    public function __invoke(): Response
    {
//        return $this->render('@profile/processed_news.html.twig');
//        return $this->render('@profile/offer_news.html.twig');
        return $this->render('@profile/edit_profile.html.twig');
//        return $this->render('@profile/edit_password.html.twig');
//        return $this->render('@profile/index.html.twig');
    }
}