<?php

declare(strict_types=1);

namespace App\Ship\Parent;

use App\Container\User\Entity\Doc\User;
use App\Ship\Action\ValidateFormAndSaveEntityAction;
use App\Ship\Validator\ControllerValidator;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Controllers are responsible for validating the request, serving the request data and building a response.
 * Validation and response, happens in separate classes, but triggered from the Controller.
 * The Controllers concept is the same as in MVC (They are the C in MVC), but with limited and predefined
 * responsibilities.
 *
 * Usually contains the *__invoke()* method, which accepts the request object and returns a response
 *
 * @see https://github.com/Mahmoudz/Porto#Controllers Detailed information
 *
 * @method User|UserInterface getUser
 */
abstract class Controller extends AbstractController
{
    public static function getSubscribedServices(): array
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                'controller_validator' => '?'.ControllerValidator::class,
                'validate_and_save_form_action' => '?'.ValidateFormAndSaveEntityAction::class,
            ]
        );
    }

    public function createDTO(string $dtoClass): DTO
    {
        if (class_exists($dtoClass) && is_subclass_of($dtoClass, DTO::class)) {
            return $dtoClass::create($this->container->get('request_stack')->getCurrentRequest());
        }

        throw new InvalidArgumentException('Invalid DTO class.');
    }

    public function isValid(DTO $dto): bool
    {
        return $this->container->get('controller_validator')->validate($dto);
    }

    protected function redirectBack(): RedirectResponse
    {
        $currentRequest = $this->container->get('request_stack')->getCurrentRequest();

        $route = $currentRequest->attributes->get('_route', $currentRequest->attributes->all());

        return $this->redirectToRoute($route, $currentRequest->attributes->get('_route_params'));
    }

    protected function validateAndSaveForm(FormInterface $form): bool
    {
        return $this->container->get('validate_and_save_form_action')->run($form);
    }
}
