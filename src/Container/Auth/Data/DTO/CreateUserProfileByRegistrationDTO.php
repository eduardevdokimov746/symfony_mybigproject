<?php

namespace App\Container\Auth\Data\DTO;

use App\Ship\Parent\DTO;

class CreateUserProfileByRegistrationDTO extends DTO
{
    public string $login;

    public string $email;

    public string $plainPassword;
}