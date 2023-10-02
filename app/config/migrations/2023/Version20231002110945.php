<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231002110945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE "users" (
                id INT NOT NULL, 
                first_name VARCHAR(255) NOT NULL, 
                last_name VARCHAR(255) NOT NULL, 
                email VARCHAR(180) NOT NULL, 
                roles JSON NOT NULL, 
                password VARCHAR(255) NOT NULL, 
                enabled BOOLEAN DEFAULT true, 
                last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                last_activity TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                created_by INT DEFAULT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                updated_by INT DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "users" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649DE12AB56 ON "users" (created_by)');
        $this->addSql('CREATE INDEX IDX_8D93D64916FE72E1 ON "users" (updated_by)');
        $this->addSql('ALTER TABLE "users" ADD CONSTRAINT FK_8D93D649DE12AB56 FOREIGN KEY (created_by) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "users" ADD CONSTRAINT FK_8D93D64916FE72E1 FOREIGN KEY (updated_by) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_8D93D649DE12AB56');
        $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_8D93D64916FE72E1');
        $this->addSql('DROP TABLE "users"');
    }
}
