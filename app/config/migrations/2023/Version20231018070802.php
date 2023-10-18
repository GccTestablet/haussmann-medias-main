<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231018070802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add distribution contract work territory tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE distribution_contract_work_territories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE distribution_contract_work_territories (id INT NOT NULL, distribution_contract_work_id INT NOT NULL, territory_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_69AD7E9C79A64941 ON distribution_contract_work_territories (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_69AD7E9C73F74AD4 ON distribution_contract_work_territories (territory_id)');
        $this->addSql('CREATE INDEX IDX_69AD7E9CDE12AB56 ON distribution_contract_work_territories (created_by)');
        $this->addSql('CREATE INDEX IDX_69AD7E9C16FE72E1 ON distribution_contract_work_territories (updated_by)');
        $this->addSql('CREATE TABLE distribution_contract_work_territory_broadcast_channels (distribution_contract_work_territory_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_territory_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX IDX_B50B4B92A8C5F5DF ON distribution_contract_work_territory_broadcast_channels (distribution_contract_work_territory_id)');
        $this->addSql('CREATE INDEX IDX_B50B4B92F6388094 ON distribution_contract_work_territory_broadcast_channels (broadcast_channel_id)');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9C79A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9C73F74AD4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9CDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9C16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territory_broadcast_channels ADD CONSTRAINT FK_B50B4B92A8C5F5DF FOREIGN KEY (distribution_contract_work_territory_id) REFERENCES distribution_contract_work_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territory_broadcast_channels ADD CONSTRAINT FK_B50B4B92F6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE distribution_contract_work_territories_id_seq CASCADE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9C79A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9C73F74AD4');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9CDE12AB56');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9C16FE72E1');
        $this->addSql('ALTER TABLE distribution_contract_work_territory_broadcast_channels DROP CONSTRAINT FK_B50B4B92A8C5F5DF');
        $this->addSql('ALTER TABLE distribution_contract_work_territory_broadcast_channels DROP CONSTRAINT FK_B50B4B92F6388094');
        $this->addSql('DROP TABLE distribution_contract_work_territories');
        $this->addSql('DROP TABLE distribution_contract_work_territory_broadcast_channels');
    }
}
