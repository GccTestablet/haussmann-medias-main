<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010102048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE works_broadcast_channels (work_id INT NOT NULL, broadcast_channel_id INT NOT NULL, PRIMARY KEY(work_id, broadcast_channel_id))');
        $this->addSql('CREATE INDEX IDX_E963739EBB3453DB ON works_broadcast_channels (work_id)');
        $this->addSql('CREATE INDEX IDX_E963739EF6388094 ON works_broadcast_channels (broadcast_channel_id)');
        $this->addSql('ALTER TABLE works_broadcast_channels ADD CONSTRAINT FK_E963739EBB3453DB FOREIGN KEY (work_id) REFERENCES works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE works_broadcast_channels ADD CONSTRAINT FK_E963739EF6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES broadcast_channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("ALTER TABLE works ADD country VARCHAR(2) DEFAULT 'FR' NOT NULL");
        $this->addSql('ALTER TABLE works ALTER country DROP DEFAULT');
        $this->addSql('ALTER TABLE works DROP origin');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works_broadcast_channels DROP CONSTRAINT FK_E963739EBB3453DB');
        $this->addSql('ALTER TABLE works_broadcast_channels DROP CONSTRAINT FK_E963739EF6388094');
        $this->addSql('DROP TABLE works_broadcast_channels');
        $this->addSql('ALTER TABLE works ADD origin VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE works DROP country');
    }
}
