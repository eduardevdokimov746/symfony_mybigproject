<?php

declare(strict_types=1);

namespace App\Container\AuthSection\ResetPassword\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220906080814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create doc_reset_password_requests table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE doc_reset_password_requests (
          id SERIAL NOT NULL,
          user_id INT NOT NULL,
          selector VARCHAR(20) NOT NULL,
          hashed_token VARCHAR(100) NOT NULL,
          requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_431CE773A76ED395 ON doc_reset_password_requests (user_id)');
        $this->addSql('COMMENT ON COLUMN doc_reset_password_requests.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN doc_reset_password_requests.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE
          doc_reset_password_requests
        ADD
          CONSTRAINT FK_431CE773A76ED395 FOREIGN KEY (user_id) REFERENCES doc_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE doc_reset_password_requests');
    }
}
