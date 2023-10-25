<?php

declare(strict_types=1);

namespace App\Tests\End2End\Contract\AcquisitionContract;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\Contract\AcquisitionContractFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;

class UpdatePageTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
            AcquisitionContractFixture::class,
            WorkFixture::class,
        ]);

        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->iAmOn('/acquisition-contracts/1/update');
    }

    public function testBackButton(): void
    {
        $this->iClickOn('Retour');
        $this->assertUrl('/acquisition-contracts');
    }

    public function testPageTitle(): void
    {
        $this->assertPageContains('Mettre à jour le contrat HF - Winnie the Pooh de la société Haussmann Medias');
    }

    public function testForm(): void
    {
        $form = $this->crawler->selectButton('Enregistrer')->form([
            'acquisition_contract_form[name]' => 'HM - Winnie Pooh',
        ]);

        $this->iClickOnElement('body');

        $this->client->submit($form);
        $this->assertUrl('/acquisition-contracts/1');

        $this->refreshCrawler();
        $this->assertPageContains('HM - Winnie Pooh');
    }
}
