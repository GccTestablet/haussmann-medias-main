<?php

declare(strict_types=1);

namespace App\Tests\End2End\Contract\AcquisitionContract;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\Contract\AcquisitionContractFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkTerritoryFixture;

class ShowPageTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
            AcquisitionContractFixture::class,
            WorkFixture::class,
            WorkTerritoryFixture::class,
        ]);

        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->iAmOn('/acquisition-contracts/1');
    }

    public function testPageTitle(): void
    {
        $this->assertPageContains('Winnie the Pooh');
    }

    public function testInfo(): void
    {
        $this->assertPageContains('Date de signature 01/01/2023');
        $this->assertPageContains('Date de début de droits 01/01/2023');
        $this->assertPageContains('Date de fin de droits 31/12/2023');
        $this->assertPageContains('Fréquence des rapports Mensuel');
        $this->assertPageContains('Fichiers');
    }

    public function testWorkList(): void
    {
        $this->assertPageContains('Ajouter une œuvre');
        $this->assertTableContains('#acquisition-contract-works-table', [
            ' ID INTERNE', 'TITRE FRANÇAIS', 'CONTRAT',
        ], [
            ['HAU000001', 'Winnie the Pooh', 'HF - Winnie the Pooh'],
        ]);

        $this->iClickOnElement('[data-datatable--table-expand-work-param="1"]');
        $this->assertTableContains('#work-territories-table', [
            'TERRITOIRE', 'CANAUX',
        ], [
            ['France', 'AVOD, SVOD'],
            ['United Kingdom', 'AVOD, SVOD, TVOD'],
        ]);
    }
}
