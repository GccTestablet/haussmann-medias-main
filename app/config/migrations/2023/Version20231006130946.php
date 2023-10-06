<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231006130946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change beneficiary to companies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contracts DROP CONSTRAINT FK_950A973ECCAAFA0');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973ECCAAFA0 FOREIGN KEY (beneficiary_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contracts DROP CONSTRAINT fk_950a973eccaafa0');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT fk_950a973eccaafa0 FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
