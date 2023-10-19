<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Setting;

use App\Entity\Setting\AdaptationCostType;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class AdaptationCostTypeFixture extends AbstractFixture
{
    final public const DUBBING_COST = 'adaptation_cost_type.dubbing_cost';
    final public const SUBTITLING_COST = 'adaptation_cost_type.subtitling_cost';
    final public const VOICE_OVER_COST = 'adaptation_cost_type.voice_over_cost';

    private const ROWS = [
        self::DUBBING_COST => [
            'name' => 'Dubbing cost',
        ],
        self::SUBTITLING_COST => [
            'name' => 'Subtitling cost',
        ],
        self::VOICE_OVER_COST => [
            'name' => 'Voice over cost',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $adaptationCostType = new AdaptationCostType();
            $this->merge($row, $adaptationCostType);

            $manager->persist($adaptationCostType);

            $this->setReference($reference, $adaptationCostType);
        }

        $manager->flush();
    }
}
