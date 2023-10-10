<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010113507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename setting tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE broadcast_channels RENAME TO setting_broadcast_channels');
        $this->addSql('ALTER SEQUENCE broadcast_channels_id_seq RENAME TO setting_broadcast_channels_id_seq');
        
        $this->addSql('ALTER TABLE broadcast_services RENAME TO setting_broadcast_services');
        $this->addSql('ALTER SEQUENCE broadcast_services_id_seq RENAME TO setting_broadcast_services_id_seq');

        $this->addSql('ALTER TABLE territories RENAME TO setting_territories');
        $this->addSql('ALTER SEQUENCE territories_id_seq RENAME TO setting_territories_id_seq');

        $this->addSql('ALTER INDEX uniq_4fe272315e237e06 RENAME TO UNIQ_86853A695E237E06');
        $this->addSql('ALTER INDEX idx_4fe27231de12ab56 RENAME TO IDX_86853A69DE12AB56');
        $this->addSql('ALTER INDEX idx_4fe2723116fe72e1 RENAME TO IDX_86853A6916FE72E1');

        $this->addSql('ALTER INDEX uniq_cfc471ee5e237e06 RENAME TO UNIQ_6A339B65E237E06');
        $this->addSql('ALTER INDEX idx_cfc471eef6388094 RENAME TO IDX_6A339B6F6388094');
        $this->addSql('ALTER INDEX idx_cfc471eede12ab56 RENAME TO IDX_6A339B6DE12AB56');
        $this->addSql('ALTER INDEX idx_cfc471ee16fe72e1 RENAME TO IDX_6A339B616FE72E1');

        $this->addSql('ALTER INDEX uniq_e0dba3b65e237e06 RENAME TO UNIQ_DB73D2615E237E06');
        $this->addSql('ALTER INDEX idx_e0dba3b6de12ab56 RENAME TO IDX_DB73D261DE12AB56');
        $this->addSql('ALTER INDEX idx_e0dba3b616fe72e1 RENAME TO IDX_DB73D26116FE72E1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE setting_broadcast_channels RENAME TO broadcast_channels');
        $this->addSql('ALTER SEQUENCE setting_broadcast_channels_id_seq RENAME TO broadcast_channels_id_seq');

        $this->addSql('ALTER TABLE setting_broadcast_services RENAME TO broadcast_services');
        $this->addSql('ALTER SEQUENCE setting_broadcast_services_id_seq RENAME TO broadcast_services_id_seq');

        $this->addSql('ALTER TABLE setting_territories RENAME TO territories');
        $this->addSql('ALTER SEQUENCE setting_territories_id_seq RENAME TO territories_id_seq');

        $this->addSql('ALTER INDEX idx_db73d26116fe72e1 RENAME TO idx_e0dba3b616fe72e1');
        $this->addSql('ALTER INDEX idx_db73d261de12ab56 RENAME TO idx_e0dba3b6de12ab56');
        $this->addSql('ALTER INDEX uniq_db73d2615e237e06 RENAME TO uniq_e0dba3b65e237e06');
        $this->addSql('ALTER INDEX idx_86853a6916fe72e1 RENAME TO idx_4fe2723116fe72e1');
        $this->addSql('ALTER INDEX idx_86853a69de12ab56 RENAME TO idx_4fe27231de12ab56');
        $this->addSql('ALTER INDEX uniq_86853a695e237e06 RENAME TO uniq_4fe272315e237e06');
        $this->addSql('ALTER INDEX idx_6a339b616fe72e1 RENAME TO idx_cfc471ee16fe72e1');
        $this->addSql('ALTER INDEX idx_6a339b6de12ab56 RENAME TO idx_cfc471eede12ab56');
        $this->addSql('ALTER INDEX idx_6a339b6f6388094 RENAME TO idx_cfc471eef6388094');
        $this->addSql('ALTER INDEX uniq_6a339b65e237e06 RENAME TO uniq_cfc471ee5e237e06');
    }
}
