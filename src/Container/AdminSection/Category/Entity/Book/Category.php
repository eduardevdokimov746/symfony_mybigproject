<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Entity\Book;

use App\Container\AdminSection\Category\Data\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'book_categories')]
class Category
{
    public const ACTIVE_DEFAULT = true;

    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'ru_name', type: 'string', length: 45)]
    private string $ruName;

    #[ORM\Column(name: 'en_name', type: 'string', length: 45)]
    private string $enName;

    #[ORM\Column(name: 'slug', type: 'string', length: 50, unique: true)]
    #[Gedmo\Slug(fields: ['enName'])]
    private string $slug;

    #[ORM\Column(name: 'active', type: 'boolean', options: ['default' => self::ACTIVE_DEFAULT])]
    private bool $active = self::ACTIVE_DEFAULT;

    public function __construct(string $ruName, string $enName)
    {
        $this->setRuName($ruName);
        $this->setEnName($enName);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRuName(): string
    {
        return $this->ruName;
    }

    public function setRuName(string $ruName): self
    {
        $this->ruName = $ruName;

        return $this;
    }

    public function getEnName(): string
    {
        return $this->enName;
    }

    public function setEnName(string $enName): self
    {
        $this->enName = $enName;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
