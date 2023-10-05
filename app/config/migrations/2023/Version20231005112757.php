<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231005112757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add connected_on column to users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD connected_on INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C445093F FOREIGN KEY (connected_on) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E9C445093F ON users (connected_on)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9C445093F');
        $this->addSql('DROP INDEX IDX_1483A5E9C445093F');
        $this->addSql('ALTER TABLE users DROP connected_on');
    }
}
