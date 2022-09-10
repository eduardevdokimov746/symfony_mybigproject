<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\UI\WEB\Controller;

use App\Container\AuthSection\Auth\Action\CreateUserProfileByRegistrationAction;
use App\Container\AuthSection\Auth\Data\DTO\CreateUserProfileByRegistrationDTO;
use App\Container\AuthSection\Auth\Validator\RegistrationValidator;
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
        private CreateUserProfileByRegistrationAction $createUserProfileByRegistrationAction,
        #[Autowire(service: 'security.authenticator.form_login.main')]
        private FormLoginAuthenticator $authenticator,
        private UserAuthenticatorInterface $userAuthenticator,
    ) {
    }

    public function __invoke(Request $request, RegistrationValidator $validator): Response
    {
        if ($validator->isValid()) {
            $user = $this->createUserProfileByRegistrationAction->run(
                CreateUserProfileByRegistrationDTO::fromValidator($validator)
            );

            $request->headers->set('referer', $this->generateUrl('profile.index'));

            return $this->userAuthenticator->authenticateUser($user, $this->authenticator, $request);
        }

        return $this->render('@auth/registration.html.twig');
    }
}
