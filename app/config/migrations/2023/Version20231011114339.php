<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231011114339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Distribution contract work broadcast channel table added';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_channels (distribution_contract_work_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX IDX_6C4CF7CC79A64941 ON distribution_contract_work_broadcast_channels (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_6C4CF7CCF6388094 ON distribution_contract_work_broadcast_channels (broadcast_channel_id)');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT FK_6C4CF7CC79A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels ADD CONSTRAINT FK_6C4CF7CCF6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT FK_6C4CF7CC79A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_channels DROP CONSTRAINT FK_6C4CF7CCF6388094');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_channels');
    }
}
