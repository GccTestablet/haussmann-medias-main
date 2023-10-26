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
        $this->assertPageContains('Droits à partir de 01/01/2023');
        $this->assertPageContains('Droits jusqu\'à 31/12/2023');
        $this->assertPageContains('Fréquence des rapports Mensuel');
        $this->assertPageContains('Files');
    }

    public function testWorkList(): void
    {
        $this->assertPageContains('Ajouter une oeuvre');
        $this->assertTableContains('#acquisition-contract-works-table', [
            'INTERNAL ID', 'NOM ORIGINAL', 'CONTRACT',
        ], [
            ['HAU000001', 'Winnie the Pooh', 'HF - Winnie the Pooh'],
        ]);

        $this->iClickOnElement('[data-datatable--table-expand-work-param="1"]');
        $this->assertTableContains('#work-territories-table', [
            'TERRITORY', 'CHANNELS',
        ], [
            ['France', 'AVOD, SVOD'],
            ['United Kingdom', 'AVOD, SVOD, TVOD'],
        ]);
    }
}