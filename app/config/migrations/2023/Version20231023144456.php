<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231023144456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add text-area to comment in work adaptation';
    }

    public function up(Schema $schema): void
    {
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_adaptations DROP comment');
    }
}
