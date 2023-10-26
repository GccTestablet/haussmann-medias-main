<?php

declare(strict_types=1);

namespace App\Tests\End2End\Contract\DistributionContract;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\Contract\DistributionContractFixture;
use App\Tests\Fixtures\Doctrine\Contract\DistributionContractWorkFixture;
use App\Tests\Fixtures\Doctrine\Setting\BroadcastChannelFixture;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;

class ShowPageTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
            DistributionContractFixture::class,
            DistributionContractWorkFixture::class,
            WorkFixture::class,
            BroadcastChannelFixture::class,
        ]);

        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->iAmOn('/distribution-contracts/1');
    }

    public function testPageTitle(): void
    {
        $this->assertPageContains('Contrat de sous-distribution du distributeur Mediawan');
    }

    public function testInfo(): void
    {
        $this->assertPageContains('Nom du contrat: MW - Winnie the Pooh');
        $this->assertPageContains('Date de signature: 01/10/2021');
        $this->assertPageContains('Canaux de diffusion:');
        $this->assertPageContains('Exclusivité:');
        $this->assertPageContains('Conditions commerciales:');
        $this->assertPageContains('Fréquence des rapports: Mensuel');
        $this->assertPageContains('Fichiers');
    }

    public function testWorkList(): void
    {
        $this->assertPageContains('Ajouter une œuvre');
        $this->assertTableContains('#distribution-contract-works-table', [
            'ŒUVRE', 'DATE DE DÉBUT DE DROITS', 'DATE DE FIN DE DROITS', 'MONTANT',
        ], [
            ['Winnie the Pooh', '01/01/2023', '31/12/2023', "50\u{202f}000,00 €"],
        ]);

        $this->iClickOnElement('[data-datatable--table-expand-work-param="1"]');
        $this->assertTableContains('#work-territories-table', [
            'TERRITORY', 'CHANNELS',
        ], [
        ]);
    }
}
