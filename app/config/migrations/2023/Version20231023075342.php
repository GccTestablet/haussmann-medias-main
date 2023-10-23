<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231023075342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add distribution contract commercial condition and signed at';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE distribution_contracts_broadcast_channels (distribution_contract_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(distribution_contract_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX IDX_BF30DD37B5DDBBA7 ON distribution_contracts_broadcast_channels (distribution_contract_id)');
        $this->addSql('CREATE INDEX IDX_BF30DD37F6388094 ON distribution_contracts_broadcast_channels (broadcast_channel_id)');
        $this->addSql('ALTER TABLE distribution_contracts_broadcast_channels ADD CONSTRAINT FK_BF30DD37B5DDBBA7 FOREIGN KEY (distribution_contract_id) REFERENCES distribution_contracts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_broadcast_channels ADD CONSTRAINT FK_BF30DD37F6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts ADD signed_at DATE DEFAULT NULL');
        $this->addSql('UPDATE distribution_contracts SET signed_at = created_at');
        $this->addSql('ALTER TABLE distribution_contracts ALTER signed_at SET NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts ADD commercial_conditions TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contracts_broadcast_channels DROP CONSTRAINT FK_BF30DD37B5DDBBA7');
        $this->addSql('ALTER TABLE distribution_contracts_broadcast_channels DROP CONSTRAINT FK_BF30DD37F6388094');
        $this->addSql('DROP TABLE distribution_contracts_broadcast_channels');
        $this->addSql('ALTER TABLE distribution_contracts DROP signed_at');
        $this->addSql('ALTER TABLE distribution_contracts DROP commercial_conditions');
    }
}
