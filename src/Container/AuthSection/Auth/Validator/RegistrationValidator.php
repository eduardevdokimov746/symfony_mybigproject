<?php

namespace App\Container\AuthSection\Auth\Validator;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Ship\Parent\Validator\PropertyValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationValidator extends PropertyValidator
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private string $login;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    private string $plainPassword;

    public function __construct(
        ValidatorInterface $validator,
        private UserRepository $userRepository
    )
    {
        parent::__construct($validator);
    }

    #[Assert\IsFalse(message: 'login_already_exists')]
    private function hasLogin(): bool
    {
        if (!isset($this->login)) return false;

        return $this->userRepository->existsBy(['login' => $this->login]);
    }

    #[Assert\IsFalse(message: 'email_already_exists')]
    private function hasEmail(): bool
    {
        if (!isset($this->email)) return false;

        return $this->userRepository->existsBy(['email' => $this->email]);
    }
}