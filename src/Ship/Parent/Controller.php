<?php

namespace App\Ship\Parent;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}