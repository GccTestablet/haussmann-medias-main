<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231023132159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add text-area to comment in work adaptation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE work_adaptations ADD comment TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE work_adaptations DROP comment');
    }
}
