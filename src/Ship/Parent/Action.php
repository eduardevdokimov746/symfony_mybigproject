<?php

declare(strict_types=1);

namespace App\Ship\Parent;

/**
 * Actions represent the Use Cases of the Application (the actions that can be taken by a User or a Software in the
 * Application).
 *
 * Actions CAN hold business logic or/and they orchestrate the Tasks to perform the business logic.
 *
 * Actions take data structures as inputs, manipulates them according to the business rules internally or through some
 * Tasks, then output a new data structures.
 *
 * Actions SHOULD NOT care how the Data is gathered, or how it will be represented.
 *
 * By just looking at the Actions folder of a Container, you can determine what Use Cases (features) your Container
 * provides. And by looking at all the Actions you can tell what an Application can do.
 *
 * It usually contains a *run()* method that accepts a request data object (for example, a DTO object) and returns data
 * for the view
 *
 * @see https://github.com/Mahmoudz/Porto#Actions Detailed information
 */
abstract class Action
{
}
