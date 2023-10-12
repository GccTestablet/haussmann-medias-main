<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231012143627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug to broadcast channel';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE setting_broadcast_channels ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE setting_broadcast_channels SET slug = LOWER(name)');
        $this->addSql('ALTER TABLE setting_broadcast_channels ALTER slug DROP DEFAULT');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86853A69989D9B62 ON setting_broadcast_channels (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_86853A69989D9B62');
        $this->addSql('ALTER TABLE setting_broadcast_channels DROP slug');
    }
}
