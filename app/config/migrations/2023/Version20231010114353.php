<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010114353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Work adaptations changes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE setting_adaptation_cost_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE setting_adaptation_cost_types (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC6101B25E237E06 ON setting_adaptation_cost_types (name)');
        $this->addSql('CREATE INDEX IDX_EC6101B2DE12AB56 ON setting_adaptation_cost_types (created_by)');
        $this->addSql('CREATE INDEX IDX_EC6101B216FE72E1 ON setting_adaptation_cost_types (updated_by)');
        $this->addSql('ALTER TABLE setting_adaptation_cost_types ADD CONSTRAINT FK_EC6101B2DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE setting_adaptation_cost_types ADD CONSTRAINT FK_EC6101B216FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_adaptations ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE work_adaptations ADD cost DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE work_adaptations DROP dubbing_cost');
        $this->addSql('ALTER TABLE work_adaptations DROP manufacturing_cost');
        $this->addSql('ALTER TABLE work_adaptations DROP media_matrix_file_cost');
        $this->addSql('ALTER TABLE work_adaptations ADD CONSTRAINT FK_3A528459C54C8C93 FOREIGN KEY (type_id) REFERENCES setting_adaptation_cost_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3A528459C54C8C93 ON work_adaptations (type_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE work_adaptations DROP CONSTRAINT FK_3A528459C54C8C93');
        $this->addSql('DROP SEQUENCE setting_adaptation_cost_types_id_seq CASCADE');
        $this->addSql('ALTER TABLE setting_adaptation_cost_types DROP CONSTRAINT FK_EC6101B2DE12AB56');
        $this->addSql('ALTER TABLE setting_adaptation_cost_types DROP CONSTRAINT FK_EC6101B216FE72E1');
        $this->addSql('DROP TABLE setting_adaptation_cost_types');
        $this->addSql('DROP INDEX IDX_3A528459C54C8C93');
        $this->addSql('ALTER TABLE work_adaptations ADD dubbing_cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE work_adaptations ADD manufacturing_cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE work_adaptations ADD media_matrix_file_cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE work_adaptations DROP type_id');
        $this->addSql('ALTER TABLE work_adaptations DROP cost');
    }
}
