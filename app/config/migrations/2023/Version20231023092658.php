<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231023092658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add acquisition contract files';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE acquisition_contract_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE acquisition_contract_files (id INT NOT NULL, acquisition_contract_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, original_file_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_82A39DB7D7DF1668 ON acquisition_contract_files (file_name)');
        $this->addSql('CREATE INDEX IDX_82A39DB75321FC57 ON acquisition_contract_files (acquisition_contract_id)');
        $this->addSql('CREATE INDEX IDX_82A39DB7DE12AB56 ON acquisition_contract_files (created_by)');
        $this->addSql('CREATE INDEX IDX_82A39DB716FE72E1 ON acquisition_contract_files (updated_by)');
        $this->addSql('ALTER TABLE acquisition_contract_files ADD CONSTRAINT FK_82A39DB75321FC57 FOREIGN KEY (acquisition_contract_id) REFERENCES acquisition_contracts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE acquisition_contract_files ADD CONSTRAINT FK_82A39DB7DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE acquisition_contract_files ADD CONSTRAINT FK_82A39DB716FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_ead20329d7df1668');
        $this->addSql('ALTER TABLE acquisition_contracts DROP file_name');
        $this->addSql('ALTER TABLE acquisition_contracts DROP original_file_name');
        $this->addSql('ALTER TABLE acquisition_contracts ALTER starts_at DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE acquisition_contract_files_id_seq CASCADE');
        $this->addSql('ALTER TABLE acquisition_contract_files DROP CONSTRAINT FK_82A39DB75321FC57');
        $this->addSql('ALTER TABLE acquisition_contract_files DROP CONSTRAINT FK_82A39DB7DE12AB56');
        $this->addSql('ALTER TABLE acquisition_contract_files DROP CONSTRAINT FK_82A39DB716FE72E1');
        $this->addSql('DROP TABLE acquisition_contract_files');
        $this->addSql('ALTER TABLE acquisition_contracts ADD file_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE acquisition_contracts ADD original_file_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE acquisition_contracts ALTER starts_at SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_ead20329d7df1668 ON acquisition_contracts (file_name)');
    }
}
