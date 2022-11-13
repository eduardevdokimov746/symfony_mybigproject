<?php

declare(strict_types=1);

namespace App\Ship\Parent;

use App\Container\User\Entity\Doc\User;
use App\Ship\Action\ValidateFormAndSaveEntityAction;
use App\Ship\Helper\Security;
use App\Ship\Validator\ControllerValidator;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controllers are responsible for validating the request, serving the request data and building a response.
 * Validation and response, happens in separate classes, but triggered from the Controller.
 * The Controllers concept is the same as in MVC (They are the C in MVC), but with limited and predefined
 * responsibilities.
 *
 * Usually contains the *__invoke()* method, which accepts the request object and returns a response
 *
 * @see https://github.com/Mahmoudz/Porto#Controllers Detailed information
 */
abstract class Controller extends AbstractController
{
    public function getUser(): ?User
    {
        return $this->container->get(Security::class)->getUser();
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                ControllerValidator::class,
                ValidateFormAndSaveEntityAction::class,
                Security::class,
            ]
        );
    }

    /**
     * @template T of DTO
     *
     * @param class-string<T> $dtoClass
     *
     * @return T
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createDTO(string $dtoClass): DTO
    {
        if (class_exists($dtoClass)) {
            /** @phpstan-ignore-next-line */
            return $dtoClass::create($this->container->get('request_stack')->getCurrentRequest());
        }

        throw new InvalidArgumentException('Invalid DTO class.');
    }

    public function isValid(DTO $dto): bool
    {
        return $this->container->get(ControllerValidator::class)->validate($dto);
    }

    protected function redirectBack(): RedirectResponse
    {
        /** @var Request $currentRequest */
        $currentRequest = $this->container->get('request_stack')->getCurrentRequest();

        /** @var string $route */
        $route = $currentRequest->attributes->get('_route');

        /** @var array<string, mixed> $params */
        $params = $currentRequest->attributes->get('_route_params');

        return $this->redirectToRoute($route, $params);
    }

    protected function validateAndSaveForm(FormInterface $form): bool
    {
        return $this->container->get(ValidateFormAndSaveEntityAction::class)->run($form);
    }
}
