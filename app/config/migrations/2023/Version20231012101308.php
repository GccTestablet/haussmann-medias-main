<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231012101308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Broadcast channel added to distribution contract work revenue';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ADD broadcast_channel_id INT NOT NULL');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues ADD CONSTRAINT FK_404FF293F6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES setting_broadcast_channels (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_404FF293F6388094 ON distribution_contract_work_revenues (broadcast_channel_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_revenues DROP CONSTRAINT FK_404FF293F6388094');
        $this->addSql('DROP INDEX IDX_404FF293F6388094');
        $this->addSql('ALTER TABLE distribution_contract_work_revenues DROP broadcast_channel_id');
    }
}
