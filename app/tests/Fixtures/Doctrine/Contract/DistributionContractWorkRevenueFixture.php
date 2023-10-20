<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\DistributionContractWorkRevenue;
use App\Tests\Fixtures\Doctrine\Setting\BroadcastChannelFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DistributionContractWorkRevenueFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
        [
            'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
            'broadcastChannel' => BroadcastChannelFixture::AVOD,
            'startsAt' => '2023-01-01',
            'endsAt' => '2023-03-31',
            'amount' => 2300.00,
        ],
        [
            'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
            'broadcastChannel' => BroadcastChannelFixture::AVOD,
            'startsAt' => '2023-04-01',
            'endsAt' => '2023-06-30',
            'amount' => 800.00,
        ],
        [
            'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
            'broadcastChannel' => BroadcastChannelFixture::AVOD,
            'startsAt' => '2023-07-01',
            'endsAt' => '2023-09-30',
            'amount' => 400.00,
        ],
        [
            'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
            'broadcastChannel' => BroadcastChannelFixture::SVOD,
            'startsAt' => '2023-01-01',
            'endsAt' => '2023-03-31',
            'amount' => 800.00,
        ],
        [
            'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
            'broadcastChannel' => BroadcastChannelFixture::SVOD,
            'startsAt' => '2023-04-01',
            'endsAt' => '2023-06-30',
            'amount' => 1200.00,
        ],
        [
            'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
            'broadcastChannel' => BroadcastChannelFixture::SVOD,
            'startsAt' => '2023-07-01',
            'endsAt' => '2023-09-30',
            'amount' => 450.50,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, ['contractWork', 'broadcastChannel']);
            $this->denormalizeDateTimeFields($row, ['startsAt', 'endsAt']);

            $distributionContractWorkRevenue = new DistributionContractWorkRevenue();
            $this->merge($row, $distributionContractWorkRevenue);

            $manager->persist($distributionContractWorkRevenue);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DistributionContractWorkFixture::class,
            BroadcastChannelFixture::class,
        ];
    }
}
