<?php

declare(strict_types=1);

namespace App\Tests\Integration\Pager\Contract;

use App\Entity\Contract\AcquisitionContract;
use App\Pager\Contract\AcquisitionContractPager;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;
use App\Tests\Integration\Pager\AbstractPagerTest;

class AcquisitionContractPagerTest extends AbstractPagerTest
{
    protected static string $pagerClass = AcquisitionContractPager::class;

    protected static array $fixtures = [
        UserCompanyFixture::class,
        WorkFixture::class,
    ];

    public function testGetPagerId(): void
    {
        $this->assertSame(
            'app-acquisition-contract-pager',
            $this->pager->getPagerId()
        );
    }

    public function testGetItems(): void
    {
        $this->assertContainsOnlyInstancesOf(AcquisitionContract::class, $this->pager->getItems());

        $this->assertSame(3, $this->pager->getItemsCount());
    }

    public function provideGetColumns(): array
    {
        return [
            [
                'id' => 'archived',
                'expected' => '{"id":"archived","header":{"callback":[],"sortable":false,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
            [
                'id' => 'name',
                'expected' => '{"id":"name","header":{"callback":[],"sortable":true,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
            [
                'id' => 'acquirer',
                'expected' => '{"id":"acquirer","header":{"callback":[],"sortable":true,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
            [
                'id' => 'beneficiary',
                'expected' => '{"id":"beneficiary","header":{"callback":[],"sortable":true,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
            [
                'id' => 'signedAt',
                'expected' => '{"id":"signedAt","header":{"callback":[],"sortable":true,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
            [
                'id' => 'period',
                'expected' => '{"id":"period","header":{"callback":[],"sortable":true,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
            [
                'id' => 'works',
                'expected' => '{"id":"works","header":{"callback":[],"sortable":false,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
            [
                'id' => 'actions',
                'expected' => '{"id":"actions","header":{"callback":[],"sortable":false,"attributes":[]},"callback":[],"visible":true,"attributes":[]}',
            ],
        ];
    }
}
