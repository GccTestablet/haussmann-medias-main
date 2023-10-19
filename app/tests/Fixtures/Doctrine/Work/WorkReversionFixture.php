<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Work;

use App\Entity\Work\WorkReversion;
use App\Tests\Fixtures\Doctrine\Setting\BroadcastChannelFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkReversionFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
        [
            'work' => WorkFixture::WINNIE_THE_POOH,
            'channel' => BroadcastChannelFixture::AVOD,
            'percentageReversion' => 50,
        ],
        [
            'work' => WorkFixture::WINNIE_THE_POOH,
            'channel' => BroadcastChannelFixture::SVOD,
            'percentageReversion' => 20,
        ],
        [
            'work' => WorkFixture::WINNIE_THE_POOH,
            'channel' => BroadcastChannelFixture::TVOD,
            'percentageReversion' => 80,
        ],
        [
            'work' => WorkFixture::SNIPER,
            'channel' => BroadcastChannelFixture::FREE_TV,
            'percentageReversion' => 50,
        ],
        [
            'work' => WorkFixture::MANEATER,
            'channel' => BroadcastChannelFixture::AVOD,
            'percentageReversion' => 50,
        ],
        [
            'work' => WorkFixture::MANEATER,
            'channel' => BroadcastChannelFixture::TVOD,
            'percentageReversion' => 70,
        ],
        [
            'work' => WorkFixture::MANEATER,
            'channel' => BroadcastChannelFixture::SVOD,
            'percentageReversion' => 20,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, ['work', 'channel']);

            $workReversion = new WorkReversion();
            $this->merge($row, $workReversion);

            $manager->persist($workReversion);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WorkFixture::class,
            BroadcastChannelFixture::class,
        ];
    }
}
