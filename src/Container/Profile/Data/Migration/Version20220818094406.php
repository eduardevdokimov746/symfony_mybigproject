<?php

declare(strict_types=1);

namespace App\Container\Profile\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220818094406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create doc_profiles table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE doc_profiles (id SERIAL NOT NULL, user_id INT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, patronymic VARCHAR(255) DEFAULT NULL, about TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA84986BA76ED395 ON doc_profiles (user_id)');
        $this->addSql('ALTER TABLE doc_profiles ADD CONSTRAINT FK_EA84986BA76ED395 FOREIGN KEY (user_id) REFERENCES doc_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE doc_profiles');
    }
}
