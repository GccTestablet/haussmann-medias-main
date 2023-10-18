<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231018070319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop distribution contract work service and channel tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT fk_ec6af41379a64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT fk_ec6af413699188d8');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT fk_6c4cf7cc79a64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT fk_6c4cf7ccf6388094');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_services');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_channels');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_services (distribution_contract_work_id INT NOT NULL, broadcast_service_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_service_id))');
        $this->addSql('CREATE INDEX idx_ec6af413699188d8 ON distribution_contract_work_broadcast_services (broadcast_service_id)');
        $this->addSql('CREATE INDEX idx_ec6af41379a64941 ON distribution_contract_work_broadcast_services (distribution_contract_work_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_channels (distribution_contract_work_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX idx_6c4cf7ccf6388094 ON distribution_contract_work_broadcast_channels (broadcast_channel_id)');
        $this->addSql('CREATE INDEX idx_6c4cf7cc79a64941 ON distribution_contract_work_broadcast_channels (distribution_contract_work_id)');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT fk_ec6af41379a64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT fk_ec6af413699188d8 FOREIGN KEY (broadcast_service_id) REFERENCES setting_broadcast_services (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT fk_6c4cf7cc79a64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT fk_6c4cf7ccf6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
