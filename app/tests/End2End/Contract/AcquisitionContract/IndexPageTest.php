<?php

declare(strict_types=1);

namespace App\Tests\End2End\Contract\AcquisitionContract;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\Contract\AcquisitionContractFixture;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;

class IndexPageTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
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

        $this->iSwitchToCompany('Chrome Films');

        $this->assertTableContains('table',
            ['CONTRACT', 'BENEFICIARY', 'PERIOD', 'WORKS'],
            [
                ['Sniper and Maneater', 'Mediawan', '01/01/2023 -', '2'],
            ]
        );
    }

    /**
     * @dataProvider provideLinks
     */
    public function testLinks(string $clickOn, string $expectedUrl): void
    {
        $this->iClickOn($clickOn);
        $this->assertUrl($expectedUrl);
    }

    /**
     * @return array<array<string, string>>
     */
    public function provideLinks(): array
    {
        return [
            [
                'clickOn' => 'Ajouter un contrat d\'acquisition',
                'expectedUrl' => '/acquisition-contracts/add',
            ],
            [
                'clickOn' => 'Winnie the Pooh',
                'expectedUrl' => '/acquisition-contracts/1',
            ],
            [
                'clickOn' => 'HKA Films',
                'expectedUrl' => '/companies/3',
            ],
        ];
    }
}
