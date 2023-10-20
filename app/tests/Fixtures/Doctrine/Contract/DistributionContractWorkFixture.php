<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use App\Tests\Fixtures\Doctrine\Work\WorkFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DistributionContractWorkFixture extends AbstractFixture implements DependentFixtureInterface
{
    final public const WINNIE_THE_POOH = 'distribution_contract_work.winnie-the-pooh';
    final public const SNIPER = 'distribution_contract_work.sniper';
    final public const MANEATER = 'distribution_contract_work.maneater';

    private const ROWS = [
        self::WINNIE_THE_POOH => [
            'distributionContract' => DistributionContractFixture::WINNIE_THE_POOH,
            'work' => WorkFixture::WINNIE_THE_POOH,
            'startsAt' => '2023-01-01',
            'endsAt' => '2023-12-31',
            'amount' => 50000.00,
        ],
        self::SNIPER => [
            'distributionContract' => DistributionContractFixture::SNIPER_AND_MANEATER,
            'work' => WorkFixture::SNIPER,
            'startsAt' => '2023-01-01',
        ],
        self::MANEATER => [
            'distributionContract' => DistributionContractFixture::SNIPER_AND_MANEATER,
            'work' => WorkFixture::MANEATER,
            'startsAt' => '2023-01-01',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $this->denormalizeReferenceFields($row, ['distributionContract', 'work']);
            $this->denormalizeDateTimeFields($row, ['startsAt', 'endsAt']);

            $contractWork = new DistributionContractWork();
            $this->merge($row, $contractWork);

            $manager->persist($contractWork);
            $this->setReference($reference, $contractWork);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DistributionContractFixture::class,
            WorkFixture::class,
        ];
    }
}
