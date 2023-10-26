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

class UpdatePageTest extends AbstractEnd2EndTestCase
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

        $this->iAmOn('/distribution-contracts/1/update');
    }

    public function testBackButton(): void
    {
        $this->iClickOn('Retour');
        $this->assertUrl('/distribution-contracts');
    }

    public function testPageTitle(): void
    {
        $this->assertPageContains('Mettre à jour le contrat MW - Winnie the Pooh de la société Haussmann Medias');
    }

    public function testForm(): void
    {
        $form = $this->crawler->selectButton('Enregistrer')->form([
            'distribution_contract_form[name]' => 'HM - Winnie the Pooh',
        ]);

        $this->iClickOnElement('body');

        $this->client->submit($form);
        $this->assertUrl('/distribution-contracts/1');

        $this->refreshCrawler();
        $this->assertPageContains('HM - Winnie the Pooh');
    }
}
