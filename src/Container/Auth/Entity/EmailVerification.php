<?php

namespace App\Container\Auth\Entity;

use App\Container\Auth\Data\Repository\EmailVerificationRepository;
use App\Container\User\Entity\Doc\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailVerificationRepository::class)]
#[ORM\Table(name: 'doc_email_verifications')]
class EmailVerification
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'emailVerification')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'verification_code', type: 'string', unique: true)]
    private string $verificationCode;

    #[ORM\Column(name: 'expired_at', type: 'datetime_immutable')]
    private DateTimeImmutable $expiredAt;

    #[ORM\Column(name: 'verified_at', type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $verifiedAt;

    public function __construct(User $user, string $verificationCode, DateTimeImmutable $expiredAt)
    {
        $this->user = $user;

        $this->verificationCode = $verificationCode;
        $this->expiredAt = $expiredAt;

        $this->user->addEmailVerification($this);
    }

    public function isVerified(): bool
    {
        return $this->verifiedAt !== null;
    }
}