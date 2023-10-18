<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231018084133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add distribution contract files';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE distribution_contracts_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE distribution_contracts_files (id INT NOT NULL, distribution_contract_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, original_file_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C1F5C291D7DF1668 ON distribution_contracts_files (file_name)');
        $this->addSql('CREATE INDEX IDX_C1F5C291B5DDBBA7 ON distribution_contracts_files (distribution_contract_id)');
        $this->addSql('CREATE INDEX IDX_C1F5C291DE12AB56 ON distribution_contracts_files (created_by)');
        $this->addSql('CREATE INDEX IDX_C1F5C29116FE72E1 ON distribution_contracts_files (updated_by)');
        $this->addSql('ALTER TABLE distribution_contracts_files ADD CONSTRAINT FK_C1F5C291B5DDBBA7 FOREIGN KEY (distribution_contract_id) REFERENCES distribution_contracts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_files ADD CONSTRAINT FK_C1F5C291DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_files ADD CONSTRAINT FK_C1F5C29116FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_4ec7e96fd7df1668');
        $this->addSql('ALTER TABLE distribution_contracts DROP file_name');
        $this->addSql('ALTER TABLE distribution_contracts DROP original_file_name');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE distribution_contracts_files_id_seq CASCADE');
        $this->addSql('ALTER TABLE distribution_contracts_files DROP CONSTRAINT FK_C1F5C291B5DDBBA7');
        $this->addSql('ALTER TABLE distribution_contracts_files DROP CONSTRAINT FK_C1F5C291DE12AB56');
        $this->addSql('ALTER TABLE distribution_contracts_files DROP CONSTRAINT FK_C1F5C29116FE72E1');
        $this->addSql('DROP TABLE distribution_contracts_files');
        $this->addSql('ALTER TABLE distribution_contracts ADD file_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE distribution_contracts ADD original_file_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_4ec7e96fd7df1668 ON distribution_contracts (file_name)');
    }
}
