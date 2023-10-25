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

class CreatePageTest extends AbstractEnd2EndTestCase
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

        $this->iAmOn('/distribution-contracts/add');
    }

    public function testBackButton(): void
    {
        $this->iClickOn('Retour');
        $this->assertUrl('/distribution-contracts');
    }

    public function testPageTitle(): void
    {
        $this->assertPageContains('Ajouter un contrat à la société Haussmann Medias');
    }

    public function testFormErrors(): void
    {
        $form = $this->crawler->selectButton('Enregistrer')->form();
        $crawler = $this->client->submit($form);

        $this->assertStringContainsString(
            'Cette valeur ne doit pas être vide',
            $crawler
                ->filter('[name="distribution_contract_form[distributor]"]')
                ->ancestors()
                ->filter('.invalid-feedback')
                ->text()
        );

        $this->assertStringContainsString(
            'Cette valeur ne doit pas être vide',
            $crawler
                ->filter('[name="distribution_contract_form[name]"]')
                ->ancestors()
                ->filter('.invalid-feedback')
                ->text()
        );

        $this->assertStringContainsString(
            'Cette valeur ne doit pas être vide',
            $crawler
                ->filter('[name="distribution_contract_form[type]"]')
                ->ancestors()
                ->filter('.invalid-feedback')
                ->text()
        );

        $this->assertStringContainsString(
            'Cette valeur ne doit pas être vide',
            $crawler
                ->filter('[name="distribution_contract_form[signedAt]"]')
                ->ancestors()
                ->filter('.invalid-feedback')
                ->text()
        );
    }

    public function testForm(): void
    {
        $form = $this->crawler->selectButton('Enregistrer')->form([
            'distribution_contract_form[distributor]' => '5',
            'distribution_contract_form[name]' => 'MDC - Winnie the Pooh',
            'distribution_contract_form[type]' => 'recurring',
            'distribution_contract_form[reportFrequency]' => 'yearly',
            'distribution_contract_form[exclusivity]' => 'This contract is exclusive',
            'distribution_contract_form[commercialConditions]' => 'These are the commercial conditions',
            'distribution_contract_form[broadcastChannels][]' => ['1', '2'],
        ]);

        $form['distribution_contract_form[signedAt]'] = '2023-03-12';
        $this->iClickOnElement('body');

        $this->client->submit($form);
        $this->assertUrl('/distribution-contracts/3');

        $this->refreshCrawler();
        $this->assertPageContains('Contrat de distribution du distributeur My Digital Company');
    }
}
