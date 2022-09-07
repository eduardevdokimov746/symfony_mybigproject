<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Entity\Doc;

use App\Container\AuthSection\ResetPassword\Data\Repository\ResetPasswordRequestRepository;
use App\Container\User\Entity\Doc\User;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
#[ORM\Table(name: 'doc_reset_password_requests')]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function __construct(User $user, DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;

        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getUser(): object
    {
        return $this->user;
    }
}