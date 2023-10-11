<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231011114045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add distribution contract works table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE distribution_contracts_works_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE distribution_contracts_works (
                id INT NOT NULL, 
                distribution_contract_id INT NOT NULL, 
                work_id INT NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )');
        $this->addSql('CREATE INDEX IDX_3125808BB5DDBBA7 ON distribution_contracts_works (distribution_contract_id)');
        $this->addSql('CREATE INDEX IDX_3125808BBB3453DB ON distribution_contracts_works (work_id)');
        $this->addSql('CREATE INDEX IDX_3125808BDE12AB56 ON distribution_contracts_works (created_by)');
        $this->addSql('CREATE INDEX IDX_3125808B16FE72E1 ON distribution_contracts_works (updated_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3125808BB5DDBBA7BB3453DB ON distribution_contracts_works (distribution_contract_id, work_id)');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808BB5DDBBA7 FOREIGN KEY (distribution_contract_id) REFERENCES distribution_contracts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808BBB3453DB FOREIGN KEY (work_id) REFERENCES works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808BDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808B16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EAD20329D7DF1668 ON acquisition_contracts (file_name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE distribution_contracts_works_id_seq CASCADE');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808BB5DDBBA7');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808BBB3453DB');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808BDE12AB56');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808B16FE72E1');
        $this->addSql('DROP TABLE distribution_contracts_works');
        $this->addSql('DROP INDEX UNIQ_EAD20329D7DF1668');
    }
}
