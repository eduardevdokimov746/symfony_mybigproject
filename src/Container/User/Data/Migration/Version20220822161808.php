<?php

declare(strict_types=1);

declare(strict_types=1);

namespace App\Container\User\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220822161808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create doc_users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE doc_users (
          id SERIAL NOT NULL,
          login VARCHAR(255) NOT NULL,
          password VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          active BOOLEAN DEFAULT true NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B6AC8BFAA08CB10 ON doc_users (login)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B6AC8BFE7927C74 ON doc_users (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE doc_users');
    }
}
