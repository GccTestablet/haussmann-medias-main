<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Work;

use App\Entity\Work\Work;
use App\Enum\Work\WorkQuotaEnum;
use App\Tests\Fixtures\Doctrine\Contract\AcquisitionContractFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkFixture extends AbstractFixture implements DependentFixtureInterface
{
    final public const WINNIE_THE_POOH = 'work.winnie_the_pooh';
    final public const SNIPER = 'work.sniper';
    final public const MANEATER = 'work.maneater';

    private const ROWS = [
        self::WINNIE_THE_POOH => [
            'internalId' => 'HAU000001',
            'imdbId' => 'tt19623240',
            'name' => 'Winnie the Pooh',
            'originalName' => 'Winnie the Pooh: Blood and Honey',
            'acquisitionContract' => AcquisitionContractFixture::WINNIE_THE_POOH,
            'countries' => ['GB'],
            'quota' => WorkQuotaEnum::EUROPEAN,
        ],
        self::SNIPER => [
            'internalId' => 'CHR000001',
            'imdbId' => 'tt0108171',
            'name' => 'Sniper',
            'originalName' => 'Sniper',
            'acquisitionContract' => AcquisitionContractFixture::SNIPER_AND_MANEATER,
            'countries' => ['US'],
            'quota' => WorkQuotaEnum::INTERNATIONAL,
        ],
        self::MANEATER => [
            'internalId' => 'CHR000002',
            'imdbId' => 'tt15262370',
            'name' => 'Maneater',
            'originalName' => 'Maneater',
            'acquisitionContract' => AcquisitionContractFixture::SNIPER_AND_MANEATER,
            'countries' => ['FR'],
            'quota' => WorkQuotaEnum::FRANCE,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $this->denormalizeReferenceFields($row, ['acquisitionContract']);

            $work = new Work();
            $this->merge($row, $work);

            $manager->persist($work);
            $this->setReference($reference, $work);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AcquisitionContractFixture::class,
        ];
    }
}
