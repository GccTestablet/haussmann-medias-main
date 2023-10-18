<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Setting;

use App\Entity\Setting\BroadcastService;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BroadcastServiceFixture extends AbstractFixture implements DependentFixtureInterface
{
    final public const NETFLIX = 'broadcast_service.netflix';
    final public const AMAZON_PRIME = 'broadcast_service.amazon_prime';
    final public const CANAL_PLUS = 'broadcast_service.canal_plus';
    final public const YOUTUBE = 'broadcast_service.youtube';

    private const ROWS = [
        self::NETFLIX => [
            'name' => 'Netflix',
            'broadcastChannel' => BroadcastChannelFixture::SVOD,
        ],
        self::AMAZON_PRIME => [
            'name' => 'Amazon Prime',
            'broadcastChannel' => BroadcastChannelFixture::SVOD,
        ],
        self::CANAL_PLUS => [
            'name' => 'Canal Plus',
            'broadcastChannel' => BroadcastChannelFixture::TVOD,
        ],
        self::YOUTUBE => [
            'name' => 'Youtube',
            'broadcastChannel' => BroadcastChannelFixture::AVOD,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $broadcastService = new BroadcastService();
            $this->denormalizeReferenceFields($row, [
                'broadcastChannel',
            ]);
            $this->merge($row, $broadcastService);

            $manager->persist($broadcastService);

            $this->setReference($reference, $broadcastService);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BroadcastChannelFixture::class,
        ];
    }
}
