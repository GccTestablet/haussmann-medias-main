<?php

declare(strict_types=1);

namespace App\Tests\End2End\Contract\DistributionContract;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\Contract\DistributionContractFixture;
use App\Tests\Fixtures\Doctrine\Contract\DistributionContractWorkFixture;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;

class IndexPageTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
            DistributionContractFixture::class,
            DistributionContractWorkFixture::class,
            WorkFixture::class,
        ]);

        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->iAmOn('/distribution-contracts');
    }

    public function testContractList(): void
    {
        $this->assertPageContains('Liste des contrats de sous-distribution');
        $this->assertTableContains('table',
            ['NOM', 'DISTRIBUTEUR', 'ŒUVRES'],
            [
                ['MW - Winnie the Pooh', 'Mediawan', 'Winnie the Pooh'],
            ]
        );

        $this->iSwitchToCompany('Chrome Films');

        $this->assertTableContains('table',
            ['NOM', 'DISTRIBUTEUR', 'ŒUVRES'],
            [
                ['MDC - Sniper and Maneater', 'My Digital Company', 'Sniper, Maneater'],
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
                'clickOn' => 'Ajouter un contrat de sous-distribution',
                'expectedUrl' => '/distribution-contracts/add',
            ],
            [
                'clickOn' => 'MW - Winnie the Pooh',
                'expectedUrl' => '/distribution-contracts/1',
            ],
            [
                'clickOn' => 'Mediawan',
                'expectedUrl' => '/companies/4',
            ],
        ];
    }
}
