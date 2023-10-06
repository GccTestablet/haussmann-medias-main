<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231006122958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add work reversion entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE work_reversions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE work_reversions (
                id INT NOT NULL, 
                work_id INT NOT NULL, 
                channel_id INT NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_by INT DEFAULT NULL, 
                percentage_reversion DOUBLE PRECISION DEFAULT 0 NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_605FCB50BB3453DB ON work_reversions (work_id)');
        $this->addSql('CREATE INDEX IDX_605FCB5072F5A1AA ON work_reversions (channel_id)');
        $this->addSql('CREATE INDEX IDX_605FCB50DE12AB56 ON work_reversions (created_by)');
        $this->addSql('CREATE INDEX IDX_605FCB5016FE72E1 ON work_reversions (updated_by)');
        $this->addSql('ALTER TABLE work_reversions ADD CONSTRAINT FK_605FCB50BB3453DB FOREIGN KEY (work_id) REFERENCES works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_reversions ADD CONSTRAINT FK_605FCB5072F5A1AA FOREIGN KEY (channel_id) REFERENCES broadcast_channels (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_reversions ADD CONSTRAINT FK_605FCB50DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_reversions ADD CONSTRAINT FK_605FCB5016FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE works ADD minimum_guaranteed_before_reversion NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE works ADD minimum_cost_of_the_top_before_reversion NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE work_reversions_id_seq CASCADE');
        $this->addSql('ALTER TABLE work_reversions DROP CONSTRAINT FK_605FCB50BB3453DB');
        $this->addSql('ALTER TABLE work_reversions DROP CONSTRAINT FK_605FCB5072F5A1AA');
        $this->addSql('ALTER TABLE work_reversions DROP CONSTRAINT FK_605FCB50DE12AB56');
        $this->addSql('ALTER TABLE work_reversions DROP CONSTRAINT FK_605FCB5016FE72E1');
        $this->addSql('DROP TABLE work_reversions');
        $this->addSql('ALTER TABLE works DROP minimum_guaranteed_before_reversion');
        $this->addSql('ALTER TABLE works DROP minimum_cost_of_the_top_before_reversion');
    }
}
