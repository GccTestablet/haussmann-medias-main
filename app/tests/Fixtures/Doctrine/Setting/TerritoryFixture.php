<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Setting;

use App\Entity\Setting\Territory;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class TerritoryFixture extends AbstractFixture
{
    final public const FRANCE = 'territory.france';
    final public const UNITED_STATES = 'territory.united_states';
    final public const UNITED_KINGDOM = 'territory.united_kingdom';

    private const ROWS = [
        self::FRANCE => [
            'name' => 'France',
        ],
        self::UNITED_STATES => [
            'name' => 'United States',
        ],
        self::UNITED_KINGDOM => [
            'name' => 'United Kingdom',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $territory = new Territory();
            $this->merge($row, $territory);

            $manager->persist($territory);

            $this->setReference($reference, $territory);
        }

        $manager->flush();
    }
}
