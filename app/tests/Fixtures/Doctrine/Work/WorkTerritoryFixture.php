<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Work;

use App\Entity\Work\WorkTerritory;
use App\Tests\Fixtures\Doctrine\Setting\BroadcastChannelFixture;
use App\Tests\Fixtures\Doctrine\Setting\TerritoryFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkTerritoryFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
        [
            'work' => WorkFixture::WINNIE_THE_POOH,
            'territory' => TerritoryFixture::FRANCE,
            'broadcastChannels' => [
                BroadcastChannelFixture::AVOD,
                BroadcastChannelFixture::SVOD,
            ],
        ],
        [
            'work' => WorkFixture::WINNIE_THE_POOH,
            'territory' => TerritoryFixture::UNITED_KINGDOM,
            'broadcastChannels' => [
                BroadcastChannelFixture::AVOD,
                BroadcastChannelFixture::SVOD,
                BroadcastChannelFixture::TVOD,
            ],
        ],
        [
            'work' => WorkFixture::SNIPER,
            'territory' => TerritoryFixture::FRANCE,
            'broadcastChannels' => [
                BroadcastChannelFixture::FREE_TV,
            ],
        ],
        [
            'work' => WorkFixture::MANEATER,
            'territory' => TerritoryFixture::UNITED_KINGDOM,
            'broadcastChannels' => [
                BroadcastChannelFixture::AVOD,
                BroadcastChannelFixture::SVOD,
                BroadcastChannelFixture::TVOD,
            ],
        ],
        [
            'work' => WorkFixture::MANEATER,
            'territory' => TerritoryFixture::UNITED_STATES,
            'broadcastChannels' => [
                BroadcastChannelFixture::AVOD,
                BroadcastChannelFixture::SVOD,
                BroadcastChannelFixture::TVOD,
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, ['work', 'territory']);
            $this->denormalizeArrayCollectionReferenceFields($row, ['broadcastChannels']);

            $workTerritory = new WorkTerritory();
            $this->merge($row, $workTerritory);

            $manager->persist($workTerritory);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WorkFixture::class,
            TerritoryFixture::class,
            BroadcastChannelFixture::class,
        ];
    }
}
