<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Data\Repository;

use App\Container\AuthSection\ResetPassword\Entity\Doc\ResetPasswordRequest;
use App\Container\User\Entity\Doc\User;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    public function createResetPasswordRequest(
        object            $user,
        DateTimeInterface $expiresAt,
        string            $selector,
        string            $hashedToken
    ): ResetPasswordRequestInterface
    {
        if ($user instanceof User)
            return new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);

        throw new InvalidArgumentException('Argument $user must be type ' . User::class);
    }
}