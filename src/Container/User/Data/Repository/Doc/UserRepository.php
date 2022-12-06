<?php

declare(strict_types=1);

namespace App\Container\User\Data\Repository\Doc;

use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Repository;
use App\Ship\Trait\GetAllWithPaginationTrait;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Repository<User>
 */
class UserRepository extends Repository implements UserLoaderInterface
{
    /** @use GetAllWithPaginationTrait<User> */
    use GetAllWithPaginationTrait;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);

        $this->setPaginator($paginator);
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->findActiveOneBy(['login' => $identifier]);
    }

    /**
     * @param array<string, mixed> $criteria
     */
    public function findActiveOneBy(array $criteria): ?User
    {
        return $this->findOneBy(array_merge($criteria, ['active' => true]));
    }

    /**
     * @param array<string, mixed> $criteria
     */
    public function existsBy(array $criteria): bool
    {
        return null !== $this->findOneBy($criteria);
    }
}
