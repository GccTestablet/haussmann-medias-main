<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231031160824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove unique indexes from works table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_f6e5024354561530');
        $this->addSql('DROP INDEX uniq_f6e502435e237e06');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX uniq_f6e5024354561530 ON works (original_name)');
        $this->addSql('CREATE UNIQUE INDEX uniq_f6e502435e237e06 ON works (name)');
    }
}
