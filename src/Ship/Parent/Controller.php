<?php

namespace App\Ship\Parent;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controllers are responsible for validating the request, serving the request data and building a response.
 * Validation and response, happens in separate classes, but triggered from the Controller.
 * The Controllers concept is the same as in MVC (They are the C in MVC), but with limited and predefined
 * responsibilities.
 *
 * Usually contains the *__invoke()* method, which accepts the request object and returns a response
 *
 * @link https://github.com/Mahmoudz/Porto#Controllers Detailed information
 */
abstract class Controller extends AbstractController
{
    protected function redirectBack(): RedirectResponse
    {
        $route = $this->container->get('request_stack')->getCurrentRequest()->attributes->get('_route');

        return $this->redirectToRoute($route);
    }
}