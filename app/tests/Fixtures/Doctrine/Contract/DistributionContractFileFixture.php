<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\DistributionContractFile;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DistributionContractFileFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
        [
            'distributionContract' => DistributionContractFixture::WINNIE_THE_POOH,
            'fileName' => 'winnie-the-pooh.pdf',
            'originalFileName' => 'winnie-the-pooh.pdf',
        ],
        [
            'distributionContract' => DistributionContractFixture::SNIPER_AND_MANEATER,
            'fileName' => 'sniper.pdf',
            'originalFileName' => 'sniper.pdf',
        ],
        [
            'distributionContract' => DistributionContractFixture::SNIPER_AND_MANEATER,
            'fileName' => 'maneater.pdf',
            'originalFileName' => 'maneater.pdf',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, ['distributionContract']);

            $distributionContractFile = new DistributionContractFile();
            $this->merge($row, $distributionContractFile);

            $manager->persist($distributionContractFile);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DistributionContractFixture::class,
        ];
    }
}
