<?php

declare(strict_types=1);

namespace App\Container\User\Entity\Doc;

use App\Container\Profile\Entity\Doc\Profile;
use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Enum\RoleEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'doc_users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const DEFAULT_ACTIVE = true;
    public const DEFAULT_EMAIL_VERIFIED = false;
    public const DEFAULT_ROLE = RoleEnum::User;

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

    #[ORM\Column(name: 'active', type: 'boolean', options: ['default' => self::DEFAULT_ACTIVE])]
    private bool $active = self::DEFAULT_ACTIVE;

    #[ORM\Column(name: 'email_verified', type: 'boolean', options: ['default' => self::DEFAULT_EMAIL_VERIFIED])]
    private bool $emailVerified = self::DEFAULT_EMAIL_VERIFIED;

    #[ORM\Column(name: 'role', type: 'string', enumType: RoleEnum::class, length: 20, options: ['default' => self::DEFAULT_ROLE])]
    private RoleEnum $role = self::DEFAULT_ROLE;

    #[ORM\OneToOne(targetEntity: Profile::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Profile $profile;

    public function __construct(
        string $login,
        string $email,
        string $plainPassword,
        callable $passwordHash
    ) {
        $this
            ->setLogin($login)
            ->setEmail($email)
            ->setPassword($passwordHash, $plainPassword)
        ;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getRole(): RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): self
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    public function disable(): self
    {
        $this->active = false;

        return $this;
    }

    public function activate(): self
    {
        $this->active = true;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getRoles(): array
    {
        return [$this->getRole()->value];
    }

    public function eraseCredentials(): void
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

    public function setPassword(callable $passwordHash, string $plainPassword): self
    {
        $this->password = $passwordHash($this, $plainPassword);

        return $this;
    }
}
