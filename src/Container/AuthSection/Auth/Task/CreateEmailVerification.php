<?php

namespace App\Container\AuthSection\Auth\Task;

use App\Container\AuthSection\Auth\Entity\Doc\EmailVerification;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Task;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CreateEmailVerification extends Task
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function run(User $user, string $verificationCode, string|DateTimeImmutable $expiredAt): EmailVerification
    {
        $emailVerification = new EmailVerification(
            $user,
            $verificationCode,
            $expiredAt instanceof DateTimeImmutable ? $expiredAt : new DateTimeImmutable($expiredAt)
        );

        $this->entityManager->persist($emailVerification);
        $this->entityManager->flush();

        return $emailVerification;
    }
}