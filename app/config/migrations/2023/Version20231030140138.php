<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231030140138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add report frequency comment column to Acquisition contracts table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE acquisition_contracts ADD report_frequency_comment TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE acquisition_contracts DROP report_frequency_comment');
    }
}
