<?php

declare(strict_types=1);

namespace App\Tests\End2End\Contract\AcquisitionContract;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\Contract\AcquisitionContractFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;

class CreatePageTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
            AcquisitionContractFixture::class,
            WorkFixture::class,
        ]);

        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->iAmOn('/acquisition-contracts/add');
    }

    public function testBackButton(): void
    {
        $this->iClickOn('Retour');
        $this->assertUrl('/acquisition-contracts');
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
                ->filter('[name="acquisition_contract_form[beneficiary]"]')
                ->ancestors()
                ->filter('.invalid-feedback')
                ->text()
        );

        $this->assertStringContainsString(
            'Cette valeur ne doit pas être vide',
            $crawler
                ->filter('[name="acquisition_contract_form[name]"]')
                ->ancestors()
                ->filter('.invalid-feedback')
                ->text()
        );

        $this->assertStringContainsString(
            'Cette valeur ne doit pas être vide',
            $crawler
                ->filter('[name="acquisition_contract_form[signedAt]"]')
                ->ancestors()
                ->filter('.invalid-feedback')
                ->text()
        );
    }

    public function testForm(): void
    {
        $form = $this->crawler->selectButton('Enregistrer')->form([
            'acquisition_contract_form[beneficiary]' => '3',
            'acquisition_contract_form[name]' => 'FAST X and Oppenheimer',
            'acquisition_contract_form[reportFrequency]' => 'yearly',
        ]);

        $form['acquisition_contract_form[signedAt]'] = '2023-03-12';
        $form['acquisition_contract_form[startsAt]'] = '2023-03-12';
        $this->iClickOnElement('body');

        $this->client->submit($form);
        $this->assertUrl('/acquisition-contracts/3');

        $this->refreshCrawler();
        $this->assertPageContains('FAST X and Oppenheimer');
    }
}
