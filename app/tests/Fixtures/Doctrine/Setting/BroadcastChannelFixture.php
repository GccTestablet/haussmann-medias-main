<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use App\Tools\Parser\StringParser;
use Doctrine\Persistence\ObjectManager;

class BroadcastChannelFixture extends AbstractFixture
{
    final public const AVOD = 'broadcast_channel.avod';
    final public const SVOD = 'broadcast_channel.svod';
    final public const TVOD = 'broadcast_channel.tvod';
    final public const FREE_TV = 'broadcast_channel.free_tv';

    private const ROWS = [
        self::AVOD => [
            'name' => 'AVOD',
        ],
        self::SVOD => [
            'name' => 'SVOD',
        ],
        self::TVOD => [
            'name' => 'TVOD',
        ],
        self::FREE_TV => [
            'name' => 'Free TV',
        ],
    ];

    public function __construct(
        private readonly StringParser $stringParser
    ) {}

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $broadcastChannel = (new BroadcastChannel())
                ->setSlug($this->stringParser->slugify($row['name']))
            ;
            $this->merge($row, $broadcastChannel);

            $manager->persist($broadcastChannel);

            $this->setReference($reference, $broadcastChannel);
        }

        $manager->flush();
    }
}
