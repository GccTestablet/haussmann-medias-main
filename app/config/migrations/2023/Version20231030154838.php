<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231030154838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add exclusive column to distribution contract work territories and work territories';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD exclusive BOOLEAN DEFAULT true NOT NULL');
        $this->addSql('ALTER TABLE work_territories ADD exclusive BOOLEAN DEFAULT true NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP exclusive');
        $this->addSql('ALTER TABLE work_territories DROP exclusive');
    }
}
