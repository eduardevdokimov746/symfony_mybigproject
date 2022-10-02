<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220916124841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create book_categories table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE book_categories (
          id SERIAL NOT NULL,
          ru_name VARCHAR(45) NOT NULL,
          en_name VARCHAR(45) NOT NULL,
          slug VARCHAR(50) NOT NULL,
          active BOOLEAN DEFAULT true NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A55E0CDB989D9B62 ON book_categories (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE book_categories');
    }
}
