<?php

namespace App\Container\User\Entity\Doc;

use App\Container\Auth\Entity\EmailVerification;
use App\Container\Profile\Entity\Doc\Profile;
use App\Container\User\Data\Repository\Doc\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
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

    #[ORM\Column(name: 'email', type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(name: 'active', type: 'boolean', options: ['default' => true])]
    private bool $active = true;

    #[ORM\OneToOne(targetEntity: Profile::class, mappedBy: 'user')]
    private Profile $profile;

    #[ORM\OneToMany(targetEntity: EmailVerification::class, mappedBy: 'user')]
    private PersistentCollection|ArrayCollection $emailVerification;

    public function __construct(
        string $login,
        string $email,
        string $plainPassword,
        callable $passwordHash
    )
    {
        $this->login = $login;
        $this->email = $email;
        $this->password = $passwordHash($this, $plainPassword);

        $this->emailVerification = new ArrayCollection();
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getEmailVerification(): EmailVerification
    {
        return $this->emailVerification;
    }

    public function setEmailVerification(EmailVerification $emailVerification): self
    {
        $this->emailVerification = $emailVerification;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}