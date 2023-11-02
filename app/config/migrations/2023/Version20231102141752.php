<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231102141752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add distribution contracts territories table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE distribution_contracts_territories (distribution_contract_id INT NOT NULL, territory_id INT NOT NULL, PRIMARY KEY(distribution_contract_id, territory_id))');
        $this->addSql('CREATE INDEX IDX_34EBB25EB5DDBBA7 ON distribution_contracts_territories (distribution_contract_id)');
        $this->addSql('CREATE INDEX IDX_34EBB25E73F74AD4 ON distribution_contracts_territories (territory_id)');
        $this->addSql('ALTER TABLE distribution_contracts_territories ADD CONSTRAINT FK_34EBB25EB5DDBBA7 FOREIGN KEY (distribution_contract_id) REFERENCES distribution_contracts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contracts_territories ADD CONSTRAINT FK_34EBB25E73F74AD4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contracts_territories DROP CONSTRAINT FK_34EBB25EB5DDBBA7');
        $this->addSql('ALTER TABLE distribution_contracts_territories DROP CONSTRAINT FK_34EBB25E73F74AD4');
        $this->addSql('DROP TABLE distribution_contracts_territories');
    }
}
