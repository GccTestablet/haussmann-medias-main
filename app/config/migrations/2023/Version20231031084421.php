<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231031084421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add quota to works';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works ADD quota VARCHAR(20) DEFAULT NULL');
        $this->addSql('UPDATE works SET quota = \'france\'');
        $this->addSql('ALTER TABLE works ALTER quota DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works DROP quota');
    }
}
