<?php

declare(strict_types=1);

declare(strict_types=1);

namespace App\Container\User\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220904065928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add email_verified column to doc_users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE doc_users ADD email_verified BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE doc_users DROP email_verified');
    }
}
