<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231031140243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename country to countries in works table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works RENAME COLUMN country TO countries');
        $this->addSql('ALTER TABLE works ALTER COLUMN countries TYPE TEXT');
        $this->addSql('ALTER TABLE works ALTER quota SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN works.countries IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE works RENAME COLUMN countries TO country TEXT NOT NULL');
        $this->addSql('ALTER TABLE works ALTER COLUMN countries TYPE VARCHAR(2)');
        $this->addSql('ALTER TABLE works ALTER quota DROP NOT NULL');
    }
}
