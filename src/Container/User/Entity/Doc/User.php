<?php

namespace App\Container\User\Entity\Doc;

use App\Container\User\Data\Repository\Doc\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'doc_users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'login', type: 'string', unique: true)]
    private string $login;

    #[ORM\Column(name: 'password', type: 'string')]
    private string $password;

    #[ORM\Column(name: 'active', type: 'boolean', options: ['default' => true])]
    private bool $active = true;

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {

    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
    }

    public function getPassword(): ?string
    {
        // TODO: Implement getPassword() method.
    }
}