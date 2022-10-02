<?php

declare(strict_types=1);

namespace App\Container\User\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220916071249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add role column to doc_users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE doc_users ADD role VARCHAR(20) DEFAULT \'ROLE_USER\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE doc_users DROP role');
    }
}
