<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231011120732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Distribution contract work broadcast service and territory table added';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE distribution_contract_work_territories (distribution_contract_work_id INT NOT NULL, territory_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, territory_id))');
        $this->addSql('CREATE INDEX IDX_69AD7E9C79A64941 ON distribution_contract_work_territories (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_69AD7E9C73F74AD4 ON distribution_contract_work_territories (territory_id)');
        $this->addSql('CREATE TABLE distribution_contract_work_broadcast_services (distribution_contract_work_id INT NOT NULL, broadcast_service_id INT NOT NULL, PRIMARY KEY(distribution_contract_work_id, broadcast_service_id))');
        $this->addSql('CREATE INDEX IDX_EC6AF41379A64941 ON distribution_contract_work_broadcast_services (distribution_contract_work_id)');
        $this->addSql('CREATE INDEX IDX_EC6AF413699188D8 ON distribution_contract_work_broadcast_services (broadcast_service_id)');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9C79A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_territories ADD CONSTRAINT FK_69AD7E9C73F74AD4 FOREIGN KEY (territory_id) REFERENCES setting_territories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT FK_EC6AF41379A64941 FOREIGN KEY (distribution_contract_work_id) REFERENCES distribution_contracts_works (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services ADD CONSTRAINT FK_EC6AF413699188D8 FOREIGN KEY (broadcast_service_id) REFERENCES setting_broadcast_services (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9C79A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_territories DROP CONSTRAINT FK_69AD7E9C73F74AD4');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT FK_EC6AF41379A64941');
        $this->addSql('ALTER TABLE distribution_contract_work_broadcast_services DROP CONSTRAINT FK_EC6AF413699188D8');
        $this->addSql('DROP TABLE distribution_contract_work_territories');
        $this->addSql('DROP TABLE distribution_contract_work_broadcast_services');
    }
}
