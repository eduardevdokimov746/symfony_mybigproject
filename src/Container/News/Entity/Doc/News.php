<?php

declare(strict_types=1);

namespace App\Container\News\Entity\Doc;

use App\Container\News\Data\Repository\NewsRepository;
use App\Container\User\Entity\Doc\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ORM\Table(name: 'doc_news')]
class News
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'title', type: 'string')]
    private string $title;

    #[ORM\Column(name: 'slug', type: 'string', unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private string $slug;

    #[ORM\Column(name: 'content', type: 'string')]
    private string $content;

    #[ORM\Column(name: 'image', type: 'string')]
    private string $image;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_user_id', nullable: false)]
    private User $author;

    #[ORM\Column(name: 'published_at', type: 'datetime_immutable', nullable: true, options: ['default' => null])]
    private ?string $publishedAt;

    public function __construct(string $title, string $content, string $image, User $author)
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setImage($image);
        $this->setAuthor($author);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?string
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?string $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
