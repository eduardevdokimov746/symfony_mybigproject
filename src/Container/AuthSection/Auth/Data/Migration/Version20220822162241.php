<?php

declare(strict_types=1);

namespace App\Container\AuthSection\Auth\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220822162241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create doc_email_verifications table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE doc_email_verifications (
          id SERIAL NOT NULL,
          user_id INT NOT NULL,
          verification_code VARCHAR(255) NOT NULL,
          expired_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          verified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEB79562E821C39F ON doc_email_verifications (verification_code)');
        $this->addSql('CREATE INDEX IDX_FEB79562A76ED395 ON doc_email_verifications (user_id)');
        $this->addSql('COMMENT ON COLUMN doc_email_verifications.expired_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN doc_email_verifications.verified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE
          doc_email_verifications
        ADD
          CONSTRAINT FK_FEB79562A76ED395 FOREIGN KEY (user_id) REFERENCES doc_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE doc_email_verifications');
    }
}
