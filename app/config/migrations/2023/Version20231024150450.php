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
        $this->addSql('ALTER TABLE works RENAME COLUMN minimum_guaranteed_before_reversion TO minimum_guaranteed');
        $this->addSql('ALTER TABLE works RENAME COLUMN inimum_cost_of_the_top_before_reversion TO ceiling_of_recoverable_costs');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works RENAME COLUMN minimum_guaranteed_before_reversion TO minimum_guaranteed');
        $this->addSql('ALTER TABLE works RENAME COLUMN inimum_cost_of_the_top_before_reversion TO ceiling_of_recoverable_costs');
    }
}
