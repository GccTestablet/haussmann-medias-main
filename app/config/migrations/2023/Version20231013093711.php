<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231013093711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add currency to tables with amount';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ADD currency VARCHAR(3) DEFAULT \'EUR\' NOT NULL');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ALTER amount SET DEFAULT \'0\'');
        $this->addSql('ALTER TABLE distribution_contracts ADD currency VARCHAR(3) DEFAULT \'EUR\' NOT NULL');
        $this->addSql('ALTER TABLE work_adaptations ADD currency VARCHAR(3) DEFAULT \'EUR\' NOT NULL');
        $this->addSql('ALTER TABLE work_adaptations RENAME COLUMN cost TO amount');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE work_adaptations DROP currency');
        $this->addSql('ALTER TABLE work_adaptations RENAME COLUMN amount TO cost');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues DROP currency');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ALTER amount DROP DEFAULT');
        $this->addSql('ALTER TABLE distribution_contracts DROP currency');
    }
}
