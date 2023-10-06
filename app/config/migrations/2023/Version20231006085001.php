<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231006085001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create work_adaptations table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE work_adaptations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE work_adaptations (
                id INT NOT NULL, 
                work_id INT NOT NULL, 
                dubbing_cost DOUBLE PRECISION DEFAULT NULL, 
                manufacturing_cost DOUBLE PRECISION DEFAULT NULL, 
                media_matrix_file_cost DOUBLE PRECISION DEFAULT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_3A528459BB3453DB ON work_adaptations (work_id)');
        $this->addSql('CREATE INDEX IDX_3A528459DE12AB56 ON work_adaptations (created_by)');
        $this->addSql('CREATE INDEX IDX_3A52845916FE72E1 ON work_adaptations (updated_by)');
        $this->addSql('ALTER TABLE work_adaptations ADD CONSTRAINT FK_3A528459BB3453DB FOREIGN KEY (work_id) REFERENCES works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_adaptations ADD CONSTRAINT FK_3A528459DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_adaptations ADD CONSTRAINT FK_3A52845916FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE work_adaptations_id_seq CASCADE');
        $this->addSql('ALTER TABLE work_adaptations DROP CONSTRAINT FK_3A528459BB3453DB');
        $this->addSql('ALTER TABLE work_adaptations DROP CONSTRAINT FK_3A528459DE12AB56');
        $this->addSql('ALTER TABLE work_adaptations DROP CONSTRAINT FK_3A52845916FE72E1');
        $this->addSql('DROP TABLE work_adaptations');
    }
}
