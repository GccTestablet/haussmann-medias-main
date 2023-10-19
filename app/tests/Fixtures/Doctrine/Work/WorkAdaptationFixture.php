<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Work;

use App\Entity\Work\WorkAdaptation;
use App\Tests\Fixtures\Doctrine\Setting\AdaptationCostTypeFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkAdaptationFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
        [
            'work' => WorkFixture::WINNIE_THE_POOH,
            'type' => AdaptationCostTypeFixture::DUBBING_COST,
            'amount' => 2300,
        ],
        [
            'work' => WorkFixture::WINNIE_THE_POOH,
            'type' => AdaptationCostTypeFixture::SUBTITLING_COST,
            'amount' => 500,
        ],
        [
            'work' => WorkFixture::SNIPER,
            'type' => AdaptationCostTypeFixture::DUBBING_COST,
            'amount' => 5000,
        ],
        [
            'work' => WorkFixture::MANEATER,
            'type' => AdaptationCostTypeFixture::DUBBING_COST,
            'amount' => 1500,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, ['work', 'type']);

            $workAdaptation = new WorkAdaptation();
            $this->merge($row, $workAdaptation);

            $manager->persist($workAdaptation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WorkFixture::class,
            AdaptationCostTypeFixture::class,
        ];
    }
}
