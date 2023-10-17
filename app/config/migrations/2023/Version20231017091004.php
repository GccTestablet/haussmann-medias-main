<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231017091004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove company type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE companies DROP type');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE companies ADD type VARCHAR(20) NOT NULL');
    }
}
