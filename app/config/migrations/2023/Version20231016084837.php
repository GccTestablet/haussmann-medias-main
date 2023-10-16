<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016084837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE distribution_contracts_work_territories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE distribution_contracts_work_territories (
                id INT NOT NULL, 
                distribution_contract_work_id INT NOT NULL, 
                territory_id INT NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_CE241A1A79A64941 ON distribution_contracts_work_territories (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_CE241A1A73F74AD4 ON distribution_contracts_work_territories (territory_id)');
        $this->addSql('CREATE INDEX IDX_CE241A1ADE12AB56 ON distribution_contracts_work_territories (created_by)');
        $this->addSql('CREATE INDEX IDX_CE241A1A16FE72E1 ON distribution_contracts_work_territories (updated_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE241A1A79A6494173F74AD4 ON distribution_contracts_work_territories (distribution_contract_work_id, territory_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_territories_broadcast_channels (distribution_contract_work_territory_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_territory_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX IDX_3450995AA8C5F5DF ON distribution_contract_work_territories_broadcast_channels (distribution_contract_work_territory_id)');
        $this->addSql('CREATE INDEX IDX_3450995AF6388094 ON distribution_contract_work_territories_broadcast_channels (broadcast_channel_id)');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories ADD CONSTRAINT FK_CE241A1A79A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories ADD CONSTRAINT FK_CE241A1A73F74AD4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories ADD CONSTRAINT FK_CE241A1ADE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories ADD CONSTRAINT FK_CE241A1A16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories_broadcast_channels ADD CONSTRAINT FK_3450995AA8C5F5DF FOREIGN KEY (distribution_contract_work_territory_id) REFERENCES distribution_contracts_work_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories_broadcast_channels ADD CONSTRAINT FK_3450995AF6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT fk_ec6af41379a64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT fk_ec6af413699188d8');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT fk_6c4cf7cc79a64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT fk_6c4cf7ccf6388094');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT fk_69ad7e9c79a64941');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT fk_69ad7e9c73f74ad4');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_services');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_channels');
        $this->addSql('DROP TABLE distribution_contract_work_territories');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE distribution_contracts_work_territories_id_seq CASCADE');
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_services (distribution_contract_work_id INT NOT NULL, broadcast_service_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_service_id))');
        $this->addSql('CREATE INDEX idx_ec6af413699188d8 ON distribution_contract_work_broadcast_services (broadcast_service_id)');
        $this->addSql('CREATE INDEX idx_ec6af41379a64941 ON distribution_contract_work_broadcast_services (distribution_contract_work_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_channels (distribution_contract_work_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX idx_6c4cf7ccf6388094 ON distribution_contract_work_broadcast_channels (broadcast_channel_id)');
        $this->addSql('CREATE INDEX idx_6c4cf7cc79a64941 ON distribution_contract_work_broadcast_channels (distribution_contract_work_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_territories (distribution_contract_work_id INT NOT NULL, territory_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, territory_id))');
        $this->addSql('CREATE INDEX idx_69ad7e9c73f74ad4 ON distribution_contract_work_territories (territory_id)');
        $this->addSql('CREATE INDEX idx_69ad7e9c79a64941 ON distribution_contract_work_territories (distribution_contract_work_id)');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT fk_ec6af41379a64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT fk_ec6af413699188d8 FOREIGN KEY (broadcast_service_id) REFERENCES setting_broadcast_services (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT fk_6c4cf7cc79a64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT fk_6c4cf7ccf6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT fk_69ad7e9c79a64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT fk_69ad7e9c73f74ad4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories DROP CONSTRAINT FK_CE241A1A79A64941');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories DROP CONSTRAINT FK_CE241A1A73F74AD4');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories DROP CONSTRAINT FK_CE241A1ADE12AB56');
        $this->addSql('ALTER TABLE distribution_contracts_work_territories DROP CONSTRAINT FK_CE241A1A16FE72E1');
        $this->addSql('ALTER TABLE distribution_contract_work_territories_broadcast_channels DROP CONSTRAINT FK_3450995AA8C5F5DF');
        $this->addSql('ALTER TABLE distribution_contract_work_territories_broadcast_channels DROP CONSTRAINT FK_3450995AF6388094');
        $this->addSql('DROP TABLE distribution_contracts_work_territories');
        $this->addSql('DROP TABLE distribution_contract_work_territories_broadcast_channels');
    }
}
