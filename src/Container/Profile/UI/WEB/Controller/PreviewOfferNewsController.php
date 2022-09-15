<?php

declare(strict_types=1);

namespace App\Container\Profile\UI\WEB\Controller;

use App\Container\Profile\Validator\OfferNewsValidator;
use App\Ship\Parent\Controller;
use App\Ship\Service\ImageResize\AvatarImageResizeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/preview-offer-news',
    name: 'preview_offer_news',
    methods: 'POST'
)]
#[IsGranted('IS_AUTHENTICATED')]
class PreviewOfferNewsController extends Controller
{
    public function __construct(
    ) {
    }

    public function __invoke(OfferNewsValidator $validator): Response
    {
        if ($validator->isValid()) {
            return $this->render('@profile/preview_offer_news.html.twig');
        }

        return $this->redirectBack();
        $file = AvatarImageResizeService::createFromUploadedFile($validator->getValidated()['avatar'])->run();

        return $this->render('@profile/test.html.twig', ['img' => base64_encode(file_get_contents($file->getPathname()))]);
    }
}
