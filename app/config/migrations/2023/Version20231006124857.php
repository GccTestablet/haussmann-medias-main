<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231006124857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add report frequency to contracts and type to companies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE companies ADD type VARCHAR(20) DEFAULT \'local_seller\' NOT NULL');
        $this->addSql('ALTER TABLE companies ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE contracts ADD report_frequency VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contracts DROP report_frequency');
        $this->addSql('ALTER TABLE companies DROP type');
    }
}
