<?php

namespace App\Container\Auth\Validator;

use App\Container\Auth\LoginForm;
use App\Ship\Parent\Validator\FormTypeValidator;

class LoginFormValidator extends FormTypeValidator
{
    public function getFormType(): string
    {
        return LoginForm::class;
    }
}