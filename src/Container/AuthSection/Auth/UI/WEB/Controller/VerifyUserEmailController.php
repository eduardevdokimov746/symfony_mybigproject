<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\UI\WEB\Controller;

use App\Container\AuthSection\Auth\Action\VerifyUserEmailAction;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/verify-user-email',
    name: 'verify_user_email',
    methods: ['GET']
)]
#[IsGranted('IS_AUTHENTICATED')]
class VerifyUserEmailController extends Controller
{
    public function __construct(
        private VerifyUserEmailAction $verifyUserEmailAction
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->isEmailVerified()) {
            return $this->redirectToRoute($this->getParameter('app_default_route'));
        }

        $error = $this->verifyUserEmailAction->run($request->getUri(), $user->getId(), $user->getEmail());

        if (null !== $error) {
            $this->addFlash('error', $error);
        }

        return $this->render('@auth/email_verified.html.twig');
    }
}
