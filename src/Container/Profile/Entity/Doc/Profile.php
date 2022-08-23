<?php

namespace App\Container\Profile\Entity\Doc;

use App\Container\Profile\Data\Repository\ProfileRepository;
use App\Container\User\Entity\Doc\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\Table(name: 'doc_profiles')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'profile')]
    #[ORM\JoinColumn(name: 'user_id', unique: true, nullable: false)]
    private User $user;

    #[ORM\Column(name: 'first_name', type: 'string', nullable: true)]
    private ?string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', nullable: true)]
    private ?string $lastName;

    #[ORM\Column(name: 'patronymic', type: 'string', nullable: true)]
    private ?string $patronymic;

    #[ORM\Column(name: 'about', type: 'text', nullable: true)]
    private ?string $about;

    #[ORM\Column(name: 'avatar', type: 'string', nullable: true)]
    private ?string $avatar;

    public function __construct(User $user)
    {
        $this->user = $user;

        $user->setProfile($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }
}