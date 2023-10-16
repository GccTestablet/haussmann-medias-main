<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231013085734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Work revenue removed and name added to contracts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE work_revenues_id_seq CASCADE');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT fk_53802394bb3453db');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT fk_538023942d863a58');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT fk_5380239472f5a1aa');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT fk_53802394de12ab56');
        $this->addSql('ALTER TABLE work_revenues DROP CONSTRAINT fk_5380239416fe72e1');
        $this->addSql('DROP TABLE work_revenues');

        $this->addSql('ALTER TABLE acquisition_contracts ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE acquisition_contracts SET name = CONCAT('Contract', id)");
        $this->addSql('ALTER TABLE acquisition_contracts ALTER name SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EAD203295E237E06 ON acquisition_contracts (name)');

        $this->addSql('ALTER TABLE distribution_contracts ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE distribution_contracts SET name = CONCAT('Contract', id)");
        $this->addSql('ALTER TABLE distribution_contracts ALTER name SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4EC7E96F5E237E06 ON distribution_contracts (name)');
        $this->addSql('ALTER TABLE setting_broadcast_channels ALTER slug SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE work_revenues_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE work_revenues (id INT NOT NULL, work_id INT NOT NULL, distributor_id INT NOT NULL, channel_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, starts_at DATE NOT NULL, ends_at DATE NOT NULL, revenue DOUBLE PRECISION DEFAULT \'0\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5380239416fe72e1 ON work_revenues (updated_by)');
        $this->addSql('CREATE INDEX idx_53802394de12ab56 ON work_revenues (created_by)');
        $this->addSql('CREATE INDEX idx_5380239472f5a1aa ON work_revenues (channel_id)');
        $this->addSql('CREATE INDEX idx_538023942d863a58 ON work_revenues (distributor_id)');
        $this->addSql('CREATE INDEX idx_53802394bb3453db ON work_revenues (work_id)');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT fk_53802394bb3453db FOREIGN KEY (work_id) REFERENCES works (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT fk_538023942d863a58 FOREIGN KEY (distributor_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT fk_5380239472f5a1aa FOREIGN KEY (channel_id) REFERENCES setting_broadcast_channels (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT fk_53802394de12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE work_revenues ADD CONSTRAINT fk_5380239416fe72e1 FOREIGN KEY (updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE setting_broadcast_channels ALTER slug DROP NOT NULL');
        $this->addSql('DROP INDEX UNIQ_EAD203295E237E06');
        $this->addSql('ALTER TABLE acquisition_contracts DROP name');
        $this->addSql('DROP INDEX UNIQ_4EC7E96F5E237E06');
        $this->addSql('ALTER TABLE distribution_contracts DROP name');
    }
}
