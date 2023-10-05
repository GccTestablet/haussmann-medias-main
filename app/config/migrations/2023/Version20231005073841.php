<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231005073841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create works table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE works_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE works (
                id INT NOT NULL, 
                contract_id INT DEFAULT NULL, 
                internal_id VARCHAR(255) NOT NULL, 
                imdb_id VARCHAR(255) DEFAULT NULL, 
                name VARCHAR(255) NOT NULL, 
                original_name VARCHAR(255) NOT NULL, 
                origin VARCHAR(20) NOT NULL, 
                year SMALLINT DEFAULT NULL, 
                duration VARCHAR(255) DEFAULT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6E50243BFDFB4D8 ON works (internal_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6E502435E237E06 ON works (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6E5024354561530 ON works (original_name)');
        $this->addSql('CREATE INDEX IDX_F6E502432576E0FD ON works (contract_id)');
        $this->addSql('CREATE INDEX IDX_F6E50243DE12AB56 ON works (created_by)');
        $this->addSql('CREATE INDEX IDX_F6E5024316FE72E1 ON works (updated_by)');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E502432576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E50243DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E5024316FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE works_id_seq CASCADE');
        $this->addSql('ALTER TABLE works DROP CONSTRAINT FK_F6E502432576E0FD');
        $this->addSql('ALTER TABLE works DROP CONSTRAINT FK_F6E50243DE12AB56');
        $this->addSql('ALTER TABLE works DROP CONSTRAINT FK_F6E5024316FE72E1');
        $this->addSql('DROP TABLE works');
    }
}
