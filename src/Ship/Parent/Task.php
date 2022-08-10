<?php

namespace App\Ship\Parent;

/**
 * The Tasks are the classes that hold the shared business logic between multiple Actions accross different Containers.
 * Every Task is responsible for a small part of the logic.
 * Tasks are optional, but in most cases you find yourself in need for them.
 *
 * It usually contains a single *run()* method or a set of methods for executing various scenarios.
 *
 * @link https://github.com/Mahmoudz/Porto#Tasks Detailed inforamtion
 */
abstract class Task
{
}