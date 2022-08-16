<?php

namespace App\Container\Profile\Entity\Doc;

use App\Container\Profile\Data\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\Table(name: 'doc_profiles')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    private ?string $email;

    private ?string $firstName;

    private ?string $lastName;

    private ?string $patronymic;

    private ?string $about;
}