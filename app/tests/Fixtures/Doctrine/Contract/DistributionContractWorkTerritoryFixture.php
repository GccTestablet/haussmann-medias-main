<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\DistributionContractWorkTerritory;
use App\Tests\Fixtures\Doctrine\Setting\BroadcastChannelFixture;
use App\Tests\Fixtures\Doctrine\Setting\TerritoryFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DistributionContractWorkTerritoryFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
           [
               'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
               'territory' => TerritoryFixture::FRANCE,
               'broadcastChannels' => [
                   BroadcastChannelFixture::AVOD,
                   BroadcastChannelFixture::SVOD,
               ],
           ],
            [
                'contractWork' => DistributionContractWorkFixture::WINNIE_THE_POOH,
                'territory' => TerritoryFixture::UNITED_KINGDOM,
                'broadcastChannels' => [
                    BroadcastChannelFixture::AVOD,
                    BroadcastChannelFixture::SVOD,
                ],
            ],
           [
               'contractWork' => DistributionContractWorkFixture::SNIPER,
               'territory' => TerritoryFixture::FRANCE,
               'broadcastChannels' => [
                   BroadcastChannelFixture::FREE_TV,
               ],
           ],
        [
            'contractWork' => DistributionContractWorkFixture::MANEATER,
            'territory' => TerritoryFixture::UNITED_KINGDOM,
            'broadcastChannels' => [
                BroadcastChannelFixture::AVOD,
                BroadcastChannelFixture::SVOD,
            ],
        ],
        [
            'contractWork' => DistributionContractWorkFixture::MANEATER,
            'territory' => TerritoryFixture::UNITED_STATES,
            'broadcastChannels' => [
                BroadcastChannelFixture::AVOD,
                BroadcastChannelFixture::SVOD,
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, ['contractWork', 'territory']);
            $this->denormalizeArrayCollectionReferenceFields($row, ['broadcastChannels']);

            $contractWorkTerritory = new DistributionContractWorkTerritory();
            $this->merge($row, $contractWorkTerritory);

            $manager->persist($contractWorkTerritory);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DistributionContractWorkFixture::class,
            TerritoryFixture::class,
            BroadcastChannelFixture::class,
        ];
    }
}
