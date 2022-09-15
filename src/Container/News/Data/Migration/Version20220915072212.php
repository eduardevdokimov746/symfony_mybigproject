<?php

declare(strict_types=1);

namespace App\Container\News\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220915072212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create doc_news table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE doc_news (
          id SERIAL NOT NULL,
          author_user_id INT NOT NULL,
          title VARCHAR(255) NOT NULL,
          slug VARCHAR(255) NOT NULL,
          content VARCHAR(255) NOT NULL,
          image VARCHAR(255) NOT NULL,
          published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_44C92E86989D9B62 ON doc_news (slug)');
        $this->addSql('CREATE INDEX IDX_44C92E86E2544CD6 ON doc_news (author_user_id)');
        $this->addSql('COMMENT ON COLUMN doc_news.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE
          doc_news
        ADD
          CONSTRAINT FK_44C92E86E2544CD6 FOREIGN KEY (author_user_id) REFERENCES doc_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE doc_news DROP CONSTRAINT FK_44C92E86E2544CD6');
        $this->addSql('DROP TABLE doc_news');
    }
}
