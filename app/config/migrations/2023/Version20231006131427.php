<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231006131427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove beneficiaries';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE beneficiaries_id_seq CASCADE');
        $this->addSql('ALTER TABLE beneficiaries DROP CONSTRAINT fk_62da72f0de12ab56');
        $this->addSql('ALTER TABLE beneficiaries DROP CONSTRAINT fk_62da72f016fe72e1');
        $this->addSql('DROP TABLE beneficiaries');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE beneficiaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE beneficiaries (id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_62da72f016fe72e1 ON beneficiaries (updated_by)');
        $this->addSql('CREATE INDEX idx_62da72f0de12ab56 ON beneficiaries (created_by)');
        $this->addSql('ALTER TABLE beneficiaries ADD CONSTRAINT fk_62da72f0de12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE beneficiaries ADD CONSTRAINT fk_62da72f016fe72e1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
