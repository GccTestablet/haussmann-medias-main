<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231017090021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add work territories';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE work_territories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE work_territories (id INT NOT NULL, work_id INT NOT NULL, territory_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FF1A4375BB3453DB ON work_territories (work_id)');
        $this->addSql('CREATE INDEX IDX_FF1A437573F74AD4 ON work_territories (territory_id)');
        $this->addSql('CREATE INDEX IDX_FF1A4375DE12AB56 ON work_territories (created_by)');
        $this->addSql('CREATE INDEX IDX_FF1A437516FE72E1 ON work_territories (updated_by)');
        $this->addSql('CREATE TABLE work_territory_broadcast_channels (work_territory_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(work_territory_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX IDX_E1A4925B7185183E ON work_territory_broadcast_channels (work_territory_id)');
        $this->addSql('CREATE INDEX IDX_E1A4925BF6388094 ON work_territory_broadcast_channels (broadcast_channel_id)');
        $this->addSql('ALTER TABLE work_territories ADD CONSTRAINT FK_FF1A4375BB3453DB FOREIGN KEY (work_id) REFERENCES works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_territories ADD CONSTRAINT FK_FF1A437573F74AD4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_territories ADD CONSTRAINT FK_FF1A4375DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_territories ADD CONSTRAINT FK_FF1A437516FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_territory_broadcast_channels ADD CONSTRAINT FK_E1A4925B7185183E FOREIGN KEY (work_territory_id) REFERENCES work_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_territory_broadcast_channels ADD CONSTRAINT FK_E1A4925BF6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE works_broadcast_channels DROP CONSTRAINT fk_e963739ebb3453db');
        $this->addSql('ALTER TABLE works_broadcast_channels DROP CONSTRAINT fk_e963739ef6388094');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT fk_69ad7e9c79a64941');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT fk_69ad7e9c73f74ad4');
        $this->addSql('ALTER TABLE acquisition_contracts_territories DROP CONSTRAINT fk_56e0c22173f74ad4');
        $this->addSql('ALTER TABLE acquisition_contracts_territories DROP CONSTRAINT fk_56e0c2215321fc57');
        $this->addSql('DROP TABLE works_broadcast_channels');
        $this->addSql('DROP TABLE distribution_contract_work_territories');
        $this->addSql('DROP TABLE acquisition_contracts_territories');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE work_territories_id_seq CASCADE');
        $this->addSql('CREATE TABLE works_broadcast_channels (work_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(work_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX idx_e963739ef6388094 ON works_broadcast_channels (broadcast_channel_id)');
        $this->addSql('CREATE INDEX idx_e963739ebb3453db ON works_broadcast_channels (work_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_territories (distribution_contract_work_id INT NOT NULL, territory_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, territory_id))');
        $this->addSql('CREATE INDEX idx_69ad7e9c73f74ad4 ON distribution_contract_work_territories (territory_id)');
        $this->addSql('CREATE INDEX idx_69ad7e9c79a64941 ON distribution_contract_work_territories (distribution_contract_work_id)');
        $this->addSql('CREATE TABLE acquisition_contracts_territories (acquisition_contract_id INT NOT NULL, territory_id INT NOT NULL, PRIMARY KEY(acquisition_contract_id, territory_id))');
        $this->addSql('CREATE INDEX idx_56e0c2215321fc57 ON acquisition_contracts_territories (acquisition_contract_id)');
        $this->addSql('CREATE INDEX idx_56e0c22173f74ad4 ON acquisition_contracts_territories (territory_id)');
        $this->addSql('ALTER TABLE works_broadcast_channels ADD CONSTRAINT fk_e963739ebb3453db FOREIGN KEY (work_id) REFERENCES works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE works_broadcast_channels ADD CONSTRAINT fk_e963739ef6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT fk_69ad7e9c79a64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT fk_69ad7e9c73f74ad4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD CONSTRAINT fk_56e0c22173f74ad4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD CONSTRAINT fk_56e0c2215321fc57 FOREIGN KEY (acquisition_contract_id) REFERENCES acquisition_contracts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_territories DROP CONSTRAINT FK_FF1A4375BB3453DB');
        $this->addSql('ALTER TABLE work_territories DROP CONSTRAINT FK_FF1A437573F74AD4');
        $this->addSql('ALTER TABLE work_territories DROP CONSTRAINT FK_FF1A4375DE12AB56');
        $this->addSql('ALTER TABLE work_territories DROP CONSTRAINT FK_FF1A437516FE72E1');
        $this->addSql('ALTER TABLE work_territory_broadcast_channels DROP CONSTRAINT FK_E1A4925B7185183E');
        $this->addSql('ALTER TABLE work_territory_broadcast_channels DROP CONSTRAINT FK_E1A4925BF6388094');
        $this->addSql('DROP TABLE work_territories');
        $this->addSql('DROP TABLE work_territory_broadcast_channels');
    }
}
