<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\UI\WEB\Controller;

use App\Container\AuthSection\Auth\Action\RegisterAction;
use App\Container\AuthSection\Auth\Data\DTO\RegisterDTO;
use App\Ship\Attribute\OnlyGuest;
use App\Ship\Parent\Controller;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

#[Route(
    path: '/registration',
    name: 'registration',
    methods: ['GET', 'POST']
)]
#[OnlyGuest]
class RegistrationController extends Controller
{
    public function __construct(
        private RegisterAction $registerAction,
//        #[Autowire(service: 'security.authenticator.form_login.main')]
//        private FormLoginAuthenticator $authenticator,
        private UserAuthenticatorInterface $userAuthenticator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if (
            $request->isMethod('POST')
            && $this->isValid($dto = $this->createDTO(RegisterDTO::class))
        ) {
            $user = $this->registerAction->run($dto);

            $request->headers->set('referer', $this->generateUrl('profile.index'));

            if (null === $response = $this->userAuthenticator->authenticateUser($user, $this->authenticator, $request)) {
                return $this->redirectToRoute($this->getParameter('app_default_route'));
            }

            return $response;
        }

        return $this->render('@auth/registration.html.twig');
    }
}
