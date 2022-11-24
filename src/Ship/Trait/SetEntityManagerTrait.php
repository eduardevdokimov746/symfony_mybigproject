<?php

declare(strict_types=1);

namespace App\Ship\Trait;

use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Contracts\Service\Attribute\Required;

trait SetEntityManagerTrait
{
    private EntityManagerInterface $entityManager;
    private bool $lazyFlush = false;

    #[Required]
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function lazy(): static
    {
        $this->lazyFlush = true;

        return $this;
    }

    protected function beginTransaction(): void
    {
        $this->checkEntityManager();

        $this->entityManager->beginTransaction();
    }

    protected function flush(): void
    {
        $this->checkEntityManager();

        if (!$this->lazyFlush) {
            $this->entityManager->flush();
        }
    }

    protected function commit(): void
    {
        $this->checkEntityManager();

        $this->entityManager->commit();
    }

    protected function rollback(): void
    {
        $this->checkEntityManager();

        $this->entityManager->rollback();
    }

    /**
     * @param list<object>|object $entity
     */
    protected function remove(array|object $entity): void
    {
        $this->checkEntityManager();

        $entities = is_array($entity) ? $entity : [$entity];

        foreach ($entities as $entityIter) {
            $this->entityManager->remove($entityIter);
        }
    }

    /**
     * @param list<object>|object $entity
     */
    protected function persist(array|object $entity): void
    {
        $this->checkEntityManager();

        $entities = is_array($entity) ? $entity : [$entity];

        foreach ($entities as $entityIter) {
            $this->entityManager->persist($entityIter);
        }
    }

    /**
     * @param list<object>|object $entity
     */
    protected function persistAndFlush(array|object $entity): void
    {
        $this->checkEntityManager();

        $this->persist($entity);

        $this->flush();
    }

    private function checkEntityManager(): void
    {
        if (!isset($this->entityManager)) {
            throw new RuntimeException('Entity manager must be set with setter before use');
        }
    }
}
