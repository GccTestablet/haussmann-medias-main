<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231005081307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add NOT NULL constraint to company_id and beneficiary_id in contracts table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contracts ALTER COLUMN company_id SET NOT NULL');
        $this->addSql('ALTER TABLE contracts ALTER COLUMN beneficiary_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contracts ALTER COLUMN company_id SET NULL');
        $this->addSql('ALTER TABLE contracts ALTER COLUMN beneficiary_id SET NULL');
    }
}
