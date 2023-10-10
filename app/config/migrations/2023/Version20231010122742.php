<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010122742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename contracts table to acquisition contracts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contracts RENAME TO acquisition_contracts');
        $this->addSql('ALTER SEQUENCE contracts_id_seq RENAME TO acquisition_contracts_id_seq');

        $this->addSql('ALTER INDEX idx_950a973979b1ad6 RENAME TO IDX_EAD20329979B1AD6');
        $this->addSql('ALTER INDEX idx_950a973eccaafa0 RENAME TO IDX_EAD20329ECCAAFA0');
        $this->addSql('ALTER INDEX idx_950a973de12ab56 RENAME TO IDX_EAD20329DE12AB56');
        $this->addSql('ALTER INDEX idx_950a97316fe72e1 RENAME TO IDX_EAD2032916FE72E1');
        $this->addSql('ALTER TABLE acquisition_contracts_territories DROP CONSTRAINT fk_56e0c2212576e0fd');
        $this->addSql('DROP INDEX idx_56e0c2212576e0fd');
        $this->addSql('ALTER TABLE acquisition_contracts_territories DROP CONSTRAINT acquisition_contracts_territories_pkey');
        $this->addSql('ALTER TABLE acquisition_contracts_territories RENAME COLUMN contract_id TO acquisition_contract_id');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD CONSTRAINT FK_56E0C2215321FC57 FOREIGN KEY (acquisition_contract_id) REFERENCES acquisition_contracts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_56E0C2215321FC57 ON acquisition_contracts_territories (acquisition_contract_id)');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD PRIMARY KEY (acquisition_contract_id, territory_id)');
        $this->addSql('ALTER TABLE works DROP CONSTRAINT fk_f6e502432576e0fd');
        $this->addSql('DROP INDEX idx_f6e502432576e0fd');
        $this->addSql('ALTER TABLE works RENAME COLUMN contract_id TO acquisition_contract_id');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E502435321FC57 FOREIGN KEY (acquisition_contract_id) REFERENCES acquisition_contracts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F6E502435321FC57 ON works (acquisition_contract_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE acquisition_contracts RENAME TO contracts');
        $this->addSql('ALTER SEQUENCE acquisition_contracts_id_seq RENAME TO contracts_id_seq');

        $this->addSql('ALTER INDEX idx_ead2032916fe72e1 RENAME TO idx_950a97316fe72e1');
        $this->addSql('ALTER INDEX idx_ead20329de12ab56 RENAME TO idx_950a973de12ab56');
        $this->addSql('ALTER INDEX idx_ead20329eccaafa0 RENAME TO idx_950a973eccaafa0');
        $this->addSql('ALTER INDEX idx_ead20329979b1ad6 RENAME TO idx_950a973979b1ad6');
        $this->addSql('ALTER TABLE works DROP CONSTRAINT FK_F6E502435321FC57');
        $this->addSql('DROP INDEX IDX_F6E502435321FC57');
        $this->addSql('ALTER TABLE works RENAME COLUMN acquisition_contract_id TO contract_id');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT fk_f6e502432576e0fd FOREIGN KEY (contract_id) REFERENCES acquisition_contracts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f6e502432576e0fd ON works (contract_id)');
        $this->addSql('ALTER TABLE acquisition_contracts_territories DROP CONSTRAINT FK_56E0C2215321FC57');
        $this->addSql('DROP INDEX IDX_56E0C2215321FC57');
        $this->addSql('DROP INDEX acquisition_contracts_territories_pkey');
        $this->addSql('ALTER TABLE acquisition_contracts_territories RENAME COLUMN acquisition_contract_id TO contract_id');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD CONSTRAINT fk_56e0c2212576e0fd FOREIGN KEY (contract_id) REFERENCES acquisition_contracts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_56e0c2212576e0fd ON acquisition_contracts_territories (contract_id)');
        $this->addSql('ALTER TABLE acquisition_contracts_territories ADD PRIMARY KEY (contract_id, territory_id)');
    }
}
