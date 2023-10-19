<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231018111024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add fields to distribution_contracts_works';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contracts_works ADD starts_at DATE DEFAULT NULL');
        $this->addSql('
            UPDATE distribution_contracts_works dcw 
            SET starts_at = (
                SELECT dc.starts_at 
                FROM distribution_contracts dc 
                WHERE dc.id = dcw.distribution_contract_id
            )
        ');
        $this->addSql('ALTER TABLE distribution_contracts_works ALTER starts_at SET NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD ends_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD amount NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_works ADD currency VARCHAR(3) DEFAULT \'EUR\' NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts DROP starts_at');
        $this->addSql('ALTER TABLE distribution_contracts DROP ends_at');
        $this->addSql('ALTER TABLE distribution_contracts DROP amount');
        $this->addSql('ALTER TABLE distribution_contracts DROP currency');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE distribution_contracts ADD starts_at DATE NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts ADD ends_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE distribution_contracts ADD amount NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE distribution_contracts ADD currency VARCHAR(3) DEFAULT \'EUR\' NOT NULL');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP starts_at');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP ends_at');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP amount');
        $this->addSql('ALTER TABLE distribution_contracts_works DROP currency');
    }
}
