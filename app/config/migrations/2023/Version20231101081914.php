<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231101081914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add archived column to entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE acquisition_contracts ADD archived BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts ADD archived BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE setting_adaptation_cost_types ADD archived BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE setting_broadcast_channels ADD archived BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE setting_broadcast_services ADD archived BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE setting_territories ADD archived BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE works ADD archived BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE setting_broadcast_services DROP archived');
        $this->addSql('ALTER TABLE works DROP archived');
        $this->addSql('ALTER TABLE distribution_contracts DROP archived');
        $this->addSql('ALTER TABLE acquisition_contracts DROP archived');
        $this->addSql('ALTER TABLE setting_territories DROP archived');
        $this->addSql('ALTER TABLE setting_broadcast_channels DROP archived');
        $this->addSql('ALTER TABLE setting_adaptation_cost_types DROP archived');
    }
}
