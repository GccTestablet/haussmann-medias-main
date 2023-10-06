<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231006143142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add work revenue entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE work_revenues_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE work_revenues (
                id INT NOT NULL, 
                work_id INT NOT NULL, 
                distributor_id INT NOT NULL, 
                channel_id INT NOT NULL, 
                starts_at DATE NOT NULL, 
                ends_at DATE NOT NULL, 
                revenue DOUBLE PRECISION DEFAULT 0 NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_53802394BB3453DB ON work_revenues (work_id)');
        $this->addSql('CREATE INDEX IDX_538023942D863A58 ON work_revenues (distributor_id)');
        $this->addSql('CREATE INDEX IDX_5380239472F5A1AA ON work_revenues (channel_id)');
        $this->addSql('CREATE INDEX IDX_53802394DE12AB56 ON work_revenues (created_by)');
        $this->addSql('CREATE INDEX IDX_5380239416FE72E1 ON work_revenues (updated_by)');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT FK_53802394BB3453DB FOREIGN KEY (work_id) REFERENCES works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT FK_538023942D863A58 FOREIGN KEY (distributor_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT FK_5380239472F5A1AA FOREIGN KEY (channel_id) REFERENCES broadcast_channels (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT FK_53802394DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT FK_5380239416FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE work_revenues_id_seq CASCADE');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT FK_53802394BB3453DB');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT FK_538023942D863A58');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT FK_5380239472F5A1AA');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT FK_53802394DE12AB56');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT FK_5380239416FE72E1');
        $this->addSql('DROP TABLE work_revenues');
    }
}
