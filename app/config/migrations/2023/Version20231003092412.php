<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231003092412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add password reset to users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD password_reset_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ALTER enabled SET DEFAULT TRUE');
        $this->addSql('ALTER INDEX uniq_8d93d649e7927c74 RENAME TO UNIQ_1483A5E9E7927C74');
        $this->addSql('ALTER INDEX idx_8d93d649de12ab56 RENAME TO IDX_1483A5E9DE12AB56');
        $this->addSql('ALTER INDEX idx_8d93d64916fe72e1 RENAME TO IDX_1483A5E916FE72E1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP password_requested_at');
        $this->addSql('ALTER TABLE users DROP password_reset_token');
        $this->addSql('ALTER TABLE users ALTER enabled DROP NOT NULL');
        $this->addSql('ALTER INDEX idx_1483a5e916fe72e1 RENAME TO idx_8d93d64916fe72e1');
        $this->addSql('ALTER INDEX idx_1483a5e9de12ab56 RENAME TO idx_8d93d649de12ab56');
        $this->addSql('ALTER INDEX uniq_1483a5e9e7927c74 RENAME TO uniq_8d93d649e7927c74');
    }
}
