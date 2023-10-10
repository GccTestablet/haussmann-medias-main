<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010155912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add distribution contract tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE distribution_contracts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE distribution_contracts_works_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE distribution_contracts (
                id INT NOT NULL, 
                company_id INT NOT NULL, 
                distributor_id INT NOT NULL, 
                type VARCHAR(20) NOT NULL, 
                file_name VARCHAR(255) DEFAULT NULL, 
                original_file_name VARCHAR(255) DEFAULT NULL, 
                starts_at DATE NOT NULL, 
                ends_at DATE DEFAULT NULL, 
                exclusivity TEXT DEFAULT NULL, 
                amount NUMERIC(10, 2) DEFAULT NULL, 
                report_frequency VARCHAR(20) DEFAULT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4EC7E96FD7DF1668 ON distribution_contracts (file_name)');
        $this->addSql('CREATE INDEX IDX_4EC7E96F979B1AD6 ON distribution_contracts (company_id)');
        $this->addSql('CREATE INDEX IDX_4EC7E96F2D863A58 ON distribution_contracts (distributor_id)');
        $this->addSql('CREATE INDEX IDX_4EC7E96FDE12AB56 ON distribution_contracts (created_by)');
        $this->addSql('CREATE INDEX IDX_4EC7E96F16FE72E1 ON distribution_contracts (updated_by)');
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
            )
        ');
        $this->addSql('CREATE INDEX IDX_3125808BB5DDBBA7 ON distribution_contracts_works (distribution_contract_id)');
        $this->addSql('CREATE INDEX IDX_3125808BBB3453DB ON distribution_contracts_works (work_id)');
        $this->addSql('CREATE INDEX IDX_3125808BDE12AB56 ON distribution_contracts_works (created_by)');
        $this->addSql('CREATE INDEX IDX_3125808B16FE72E1 ON distribution_contracts_works (updated_by)');
        $this->addSql('CREATE TABLE distribution_contract_work_territories (distribution_contract_work_id INT NOT NULL, territory_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, territory_id))');
        $this->addSql('CREATE INDEX IDX_69AD7E9C79A64941 ON distribution_contract_work_territories (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_69AD7E9C73F74AD4 ON distribution_contract_work_territories (territory_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_channels (distribution_contract_work_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX IDX_6C4CF7CC79A64941 ON distribution_contract_work_broadcast_channels (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_6C4CF7CCF6388094 ON distribution_contract_work_broadcast_channels (broadcast_channel_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_services (distribution_contract_work_id INT NOT NULL, broadcast_service_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_service_id))');
        $this->addSql('CREATE INDEX IDX_EC6AF41379A64941 ON distribution_contract_work_broadcast_services (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_EC6AF413699188D8 ON distribution_contract_work_broadcast_services (broadcast_service_id)');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96F979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96F2D863A58 FOREIGN KEY (distributor_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96F16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808BB5DDBBA7 FOREIGN KEY (distribution_contract_id) REFERENCES distribution_contracts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808BBB3453DB FOREIGN KEY (work_id) REFERENCES works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808BDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD CONSTRAINT FK_3125808B16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9C79A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9C73F74AD4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT FK_6C4CF7CC79A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT FK_6C4CF7CCF6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT FK_EC6AF41379A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT FK_EC6AF413699188D8 FOREIGN KEY (broadcast_service_id) REFERENCES setting_broadcast_services (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EAD20329D7DF1668 ON acquisition_contracts (file_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3125808BB5DDBBA7BB3453DB ON distribution_contracts_works (distribution_contract_id, work_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE distribution_contracts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE distribution_contracts_works_id_seq CASCADE');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96F979B1AD6');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96F2D863A58');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96FDE12AB56');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96F16FE72E1');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808BB5DDBBA7');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808BBB3453DB');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808BDE12AB56');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP CONSTRAINT FK_3125808B16FE72E1');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9C79A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9C73F74AD4');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT FK_6C4CF7CC79A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT FK_6C4CF7CCF6388094');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT FK_EC6AF41379A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT FK_EC6AF413699188D8');
        $this->addSql('DROP TABLE distribution_contracts');
        $this->addSql('DROP TABLE distribution_contracts_works');
        $this->addSql('DROP TABLE distribution_contract_work_territories');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_channels');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_services');
        $this->addSql('DROP INDEX UNIQ_EAD20329D7DF1668');
        $this->addSql('DROP INDEX UNIQ_3125808BB5DDBBA7BB3453DB');
    }
}
