<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20231024150450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Editing variable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works ADD minimum_guaranteed NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE works ADD ceiling_of_recoverable_costs NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE works DROP minimum_guaranteed_before_reversion');
        $this->addSql('ALTER TABLE works DROP minimum_cost_of_the_top_before_reversion');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE works ADD minimum_guaranteed_before_reversion NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE works ADD minimum_cost_of_the_top_before_reversion NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE works DROP minimum_guaranteed');
        $this->addSql('ALTER TABLE works DROP ceiling_of_recoverable_costs');
    }
}
