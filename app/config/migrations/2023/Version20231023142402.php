<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231023142402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add not null constraint to file_name and original_file_name columns';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE acquisition_contract_files ALTER file_name SET NOT NULL');
        $this->addSql('ALTER TABLE acquisition_contract_files ALTER original_file_name SET NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_files ALTER file_name SET NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_files ALTER original_file_name SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE acquisition_contract_files ALTER file_name DROP NOT NULL');
        $this->addSql('ALTER TABLE acquisition_contract_files ALTER original_file_name DROP NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_files ALTER file_name DROP NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_files ALTER original_file_name DROP NOT NULL');
    }
}
