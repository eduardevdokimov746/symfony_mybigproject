<?php

declare(strict_types=1);

namespace App\Ship\Service\Doctrine;

use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\EntityManagerInterface;

class PostgresPurger implements ORMPurgerInterface
{
    private EntityManagerInterface $entityManager;
    private array $excludeTables = [
        'doctrine_migration_versions',
    ];

    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function purge(): void
    {
        $sql = 'TRUNCATE TABLE %s RESTART IDENTITY';

        $tables = array_map(
            fn (Table $table) => $table->getName(),
            $this->entityManager->getConnection()->createSchemaManager()->listTables()
        );

        $this->entityManager->getConnection()->executeQuery(
            sprintf($sql, implode(',', array_diff($tables, $this->excludeTables)))
        );
    }
}
