<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231004084556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add beneficiaries and contracts tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE beneficiaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contracts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE beneficiaries (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_62DA72F0DE12AB56 ON beneficiaries (created_by)');
        $this->addSql('CREATE INDEX IDX_62DA72F016FE72E1 ON beneficiaries (updated_by)');
        $this->addSql('
            CREATE TABLE contracts (
                id INT NOT NULL, 
                company_id INT DEFAULT NULL, 
                beneficiary_id INT DEFAULT NULL, 
                file_name VARCHAR(255) NOT NULL, 
                original_file_name VARCHAR(255) NOT NULL, 
                signed_at DATE NOT NULL, 
                starts_at DATE NOT NULL, 
                ends_at DATE DEFAULT NULL, 
                territories TEXT DEFAULT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_950A973979B1AD6 ON contracts (company_id)');
        $this->addSql('CREATE INDEX IDX_950A973ECCAAFA0 ON contracts (beneficiary_id)');
        $this->addSql('CREATE INDEX IDX_950A973DE12AB56 ON contracts (created_by)');
        $this->addSql('CREATE INDEX IDX_950A97316FE72E1 ON contracts (updated_by)');
        $this->addSql('ALTER TABLE beneficiaries ADD CONSTRAINT FK_62DA72F0DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE beneficiaries ADD CONSTRAINT FK_62DA72F016FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973ECCAAFA0 FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A97316FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ALTER enabled SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE beneficiaries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contracts_id_seq CASCADE');
        $this->addSql('ALTER TABLE beneficiaries DROP CONSTRAINT FK_62DA72F0DE12AB56');
        $this->addSql('ALTER TABLE beneficiaries DROP CONSTRAINT FK_62DA72F016FE72E1');
        $this->addSql('ALTER TABLE contracts DROP CONSTRAINT FK_950A973979B1AD6');
        $this->addSql('ALTER TABLE contracts DROP CONSTRAINT FK_950A973ECCAAFA0');
        $this->addSql('ALTER TABLE contracts DROP CONSTRAINT FK_950A973DE12AB56');
        $this->addSql('ALTER TABLE contracts DROP CONSTRAINT FK_950A97316FE72E1');
        $this->addSql('DROP TABLE beneficiaries');
        $this->addSql('DROP TABLE contracts');
        $this->addSql('ALTER TABLE users ALTER enabled DROP NOT NULL');
    }
}
