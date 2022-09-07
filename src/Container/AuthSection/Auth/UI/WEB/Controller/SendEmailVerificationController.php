<?php

namespace App\Container\AuthSection\Auth\UI\WEB\Controller;

use App\Container\AuthSection\Auth\Action\SendEmailVerificationAction;
use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/send-email-verification',
    name: 'send_email_verification',
    methods: ['GET']
)]
#[IsGranted('IS_AUTHENTICATED')]
class SendEmailVerificationController extends Controller
{
    public function __construct(
        private SendEmailVerificationAction $sendEmailVerificationAction,
        private RateLimiterFactory          $sendEmailLimiter,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        if ($this->getUser()->isEmailVerified())
            return $this->redirectToRoute($this->getParameter('app_default_route'));

        if ($this->sendEmailLimiter->create($request->getClientIp())->consume()->isAccepted())
            $signatureComponents = $this->sendEmailVerificationAction->run($this->getUser());
        else
            $signatureComponents = $this->sendEmailVerificationAction->getFakeSignatureComponents($this->getUser());

        return $this->render('@auth/email_sent.html.twig', ['signature_components' => $signatureComponents]);
    }
}