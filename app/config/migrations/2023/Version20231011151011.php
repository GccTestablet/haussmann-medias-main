<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231011151011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add distribution contract work revenue table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE distribution_contract_work_revenues_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE distribution_contract_work_revenues (
                id INT NOT NULL, 
                distribution_contract_work_id INT NOT NULL, 
                starts_at DATE NOT NULL,
                ends_at DATE NOT NULL, 
                amount NUMERIC(10, 2) NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_404FF29379A64941 ON distribution_contract_work_revenues (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_404FF293DE12AB56 ON distribution_contract_work_revenues (created_by)');
        $this->addSql('CREATE INDEX IDX_404FF29316FE72E1 ON distribution_contract_work_revenues (updated_by)');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ADD CONSTRAINT FK_404FF29379A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ADD CONSTRAINT FK_404FF293DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ADD CONSTRAINT FK_404FF29316FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE distribution_contract_work_revenues_id_seq CASCADE');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues DROP CONSTRAINT FK_404FF29379A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues DROP CONSTRAINT FK_404FF293DE12AB56');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues DROP CONSTRAINT FK_404FF29316FE72E1');
        $this->addSql('DROP TABLE distribution_contract_work_revenues');
    }
}
