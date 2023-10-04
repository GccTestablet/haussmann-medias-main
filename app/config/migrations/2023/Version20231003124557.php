<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231003124557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add company and user_company tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE companies_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE companies (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(id)
           )
       ');
        $this->addSql('CREATE INDEX IDX_8244AA3ADE12AB56 ON companies (created_by)');
        $this->addSql('CREATE INDEX IDX_8244AA3A16FE72E1 ON companies (updated_by)');
        $this->addSql('
            CREATE TABLE users_companies (
                user_id INT NOT NULL, 
                company_id INT NOT NULL, 
                permission VARCHAR(20) NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL, 
                PRIMARY KEY(user_id, company_id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_E439D0DBA76ED395 ON users_companies (user_id)');
        $this->addSql('CREATE INDEX IDX_E439D0DB979B1AD6 ON users_companies (company_id)');
        $this->addSql('CREATE INDEX IDX_E439D0DBDE12AB56 ON users_companies (created_by)');
        $this->addSql('CREATE INDEX IDX_E439D0DB16FE72E1 ON users_companies (updated_by)');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3ADE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3A16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_companies ADD CONSTRAINT FK_E439D0DBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_companies ADD CONSTRAINT FK_E439D0DB979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_companies ADD CONSTRAINT FK_E439D0DBDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_companies ADD CONSTRAINT FK_E439D0DB16FE72E1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE companies_id_seq CASCADE');
        $this->addSql('ALTER TABLE companies DROP CONSTRAINT FK_8244AA3ADE12AB56');
        $this->addSql('ALTER TABLE companies DROP CONSTRAINT FK_8244AA3A16FE72E1');
        $this->addSql('ALTER TABLE users_companies DROP CONSTRAINT FK_E439D0DBA76ED395');
        $this->addSql('ALTER TABLE users_companies DROP CONSTRAINT FK_E439D0DB979B1AD6');
        $this->addSql('ALTER TABLE users_companies DROP CONSTRAINT FK_E439D0DBDE12AB56');
        $this->addSql('ALTER TABLE users_companies DROP CONSTRAINT FK_E439D0DB16FE72E1');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE users_companies');
    }
}
