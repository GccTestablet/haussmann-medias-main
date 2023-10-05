<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231005102646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE broadcast_channels_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE broadcast_services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE broadcast_channels (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FE272315E237E06 ON broadcast_channels (name)');
        $this->addSql('CREATE INDEX IDX_4FE27231DE12AB56 ON broadcast_channels (created_by)');
        $this->addSql('CREATE INDEX IDX_4FE2723116FE72E1 ON broadcast_channels (updated_by)');
        $this->addSql('
            CREATE TABLE broadcast_services (
                id INT NOT NULL, 
                broadcast_channel_id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFC471EE5E237E06 ON broadcast_services (name)');
        $this->addSql('CREATE INDEX IDX_CFC471EEF6388094 ON broadcast_services (broadcast_channel_id)');
        $this->addSql('CREATE INDEX IDX_CFC471EEDE12AB56 ON broadcast_services (created_by)');
        $this->addSql('CREATE INDEX IDX_CFC471EE16FE72E1 ON broadcast_services (updated_by)');
        $this->addSql('ALTER TABLE broadcast_channels ADD CONSTRAINT FK_4FE27231DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE broadcast_channels ADD CONSTRAINT FK_4FE2723116FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE broadcast_services ADD CONSTRAINT FK_CFC471EEF6388094 FOREIGN KEY (broadcast_channel_id) REFERENCES broadcast_channels (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE broadcast_services ADD CONSTRAINT FK_CFC471EEDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE broadcast_services ADD CONSTRAINT FK_CFC471EE16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE broadcast_channels_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE broadcast_services_id_seq CASCADE');
        $this->addSql('ALTER TABLE broadcast_channels DROP CONSTRAINT FK_4FE27231DE12AB56');
        $this->addSql('ALTER TABLE broadcast_channels DROP CONSTRAINT FK_4FE2723116FE72E1');
        $this->addSql('ALTER TABLE broadcast_services DROP CONSTRAINT FK_CFC471EEF6388094');
        $this->addSql('ALTER TABLE broadcast_services DROP CONSTRAINT FK_CFC471EEDE12AB56');
        $this->addSql('ALTER TABLE broadcast_services DROP CONSTRAINT FK_CFC471EE16FE72E1');
        $this->addSql('DROP TABLE broadcast_channels');
        $this->addSql('DROP TABLE broadcast_services');
    }
}
