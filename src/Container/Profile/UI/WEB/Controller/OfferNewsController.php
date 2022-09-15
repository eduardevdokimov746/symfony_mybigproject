<?php

declare(strict_types=1);

namespace App\Container\Profile\UI\WEB\Controller;

use App\Container\Profile\Validator\OfferNewsValidator;
use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/offer-news',
    name: 'offer_news',
    methods: ['GET', 'POST']
)]
#[IsGranted('IS_AUTHENTICATED')]
class OfferNewsController extends Controller
{
    public function __invoke(OfferNewsValidator $validator): Response
    {
        if ($validator->isValid()) {

        }

        return $this->render('@profile/offer_news.html.twig');
    }
}
