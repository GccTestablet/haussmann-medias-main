<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231011113648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Distribution contracts table added';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE distribution_contracts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE distribution_contracts (
                id INT NOT NULL, 
                company_id INT NOT NULL, 
                distributor_id INT NOT NULL, 
                type VARCHAR(20) NOT NULL,
                file_name VARCHAR(255) DEFAULT NULL, 
                original_file_name VARCHAR(255) DEFAULT NULL, 
                starts_at DATE NOT NULL, 
                ends_at DATE DEFAULT NULL, 
                exclusivity TEXT DEFAULT NULL, 
                amount NUMERIC(10, 2) DEFAULT NULL, 
                report_frequency VARCHAR(20) DEFAULT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4EC7E96FD7DF1668 ON distribution_contracts (file_name)');
        $this->addSql('CREATE INDEX IDX_4EC7E96F979B1AD6 ON distribution_contracts (company_id)');
        $this->addSql('CREATE INDEX IDX_4EC7E96F2D863A58 ON distribution_contracts (distributor_id)');
        $this->addSql('CREATE INDEX IDX_4EC7E96FDE12AB56 ON distribution_contracts (created_by)');
        $this->addSql('CREATE INDEX IDX_4EC7E96F16FE72E1 ON distribution_contracts (updated_by)');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96F979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96F2D863A58 FOREIGN KEY (distributor_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts ADD CONSTRAINT FK_4EC7E96F16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE distribution_contracts_id_seq CASCADE');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96F979B1AD6');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96F2D863A58');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96FDE12AB56');
        $this->addSql('ALTER TABLE distribution_contracts DROP CONSTRAINT FK_4EC7E96F16FE72E1');
        $this->addSql('DROP TABLE distribution_contracts');
    }
}
