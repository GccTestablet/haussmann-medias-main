<?php

declare(strict_types=1);

namespace App\Tests\End2End\Contract\AcquisitionContract;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\Contract\AcquisitionContractFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;

class IndexPageTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
            AcquisitionContractFixture::class,
            WorkFixture::class,
        ]);

        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->iAmOn('/acquisition-contracts');
    }

    public function testContractList(): void
    {
        $this->assertPageContains('Liste des contrats d\'acquisition');
        $this->assertTableContains('table',
            ['CONTRACT', 'BENEFICIARY', 'PERIOD', 'WORKS'],
            [
                ['Winnie the Pooh', 'HKA Films', '01/01/2023 - 31/12/2023', '1'],
            ]
        );
    }

    public function testAddContract(): void
    {
        $this->iClickOn('Ajouter un contrat d\'acquisition');
        $this->assertUrl('/acquisition-contracts/add');

        $this->iClickOn('Retour');
        $this->assertUrl('/acquisition-contracts');
    }

    public function testContractLink(): void
    {
        $this->iClickOn('Winnie the Pooh');
        $this->assertUrl('/acquisition-contracts/1');
    }

    public function testBeneficiaryLink(): void
    {
        $this->iClickOn('HKA Films');
        $this->assertUrl('/companies/3');
    }
}
