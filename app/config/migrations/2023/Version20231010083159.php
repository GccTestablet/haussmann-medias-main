<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010083159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Territory and Acquisition contract territories entities added';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE territories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE acquisition_contracts_territories (
                contract_id INT NOT NULL, 
                territory_id INT NOT NULL, 
                PRIMARY KEY(contract_id, territory_id)
            )
       ');
        $this->addSql('CREATE INDEX IDX_56E0C2212576E0FD ON acquisition_contracts_territories (contract_id)');
        $this->addSql('CREATE INDEX IDX_56E0C22173F74AD4 ON acquisition_contracts_territories (territory_id)');
        $this->addSql('
            CREATE TABLE territories (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                description TEXT DEFAULT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E0DBA3B65E237E06 ON territories (name)');
        $this->addSql('CREATE INDEX IDX_E0DBA3B6DE12AB56 ON territories (created_by)');
        $this->addSql('CREATE INDEX IDX_E0DBA3B616FE72E1 ON territories (updated_by)');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD CONSTRAINT FK_56E0C2212576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD CONSTRAINT FK_56E0C22173F74AD4 FOREIGN KEY (territory_id) REFERENCES territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE territories ADD CONSTRAINT FK_E0DBA3B6DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE territories ADD CONSTRAINT FK_E0DBA3B616FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contracts DROP territories');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE territories_id_seq CASCADE');
        $this->addSql('ALTER TABLE acquisition_contracts_territories DROP CONSTRAINT FK_56E0C2212576E0FD');
        $this->addSql('ALTER TABLE acquisition_contracts_territories DROP CONSTRAINT FK_56E0C22173F74AD4');
        $this->addSql('DROP INDEX UNIQ_E0DBA3B65E237E06');
        $this->addSql('ALTER TABLE territories DROP CONSTRAINT FK_E0DBA3B6DE12AB56');
        $this->addSql('ALTER TABLE territories DROP CONSTRAINT FK_E0DBA3B616FE72E1');
        $this->addSql('DROP TABLE acquisition_contracts_territories');
        $this->addSql('DROP TABLE territories');
        $this->addSql('ALTER TABLE contracts ADD territories TEXT DEFAULT NULL');
    }
}
