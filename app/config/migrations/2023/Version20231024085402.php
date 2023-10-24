<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231024085402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add current to work';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works ADD currency VARCHAR(3) DEFAULT \'EUR\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works DROP currency');
    }
}
