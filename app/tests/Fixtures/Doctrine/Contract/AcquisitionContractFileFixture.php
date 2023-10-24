<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\AcquisitionContractFile;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AcquisitionContractFileFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
        [
            'acquisitionContract' => AcquisitionContractFixture::WINNIE_THE_POOH,
            'fileName' => 'winnie-the-pooh.pdf',
            'originalFileName' => 'winnie-the-pooh.pdf',
        ],
        [
            'acquisitionContract' => AcquisitionContractFixture::SNIPER_AND_MANEATER,
            'fileName' => 'sniper.pdf',
            'originalFileName' => 'sniper.pdf',
        ],
        [
            'acquisitionContract' => AcquisitionContractFixture::SNIPER_AND_MANEATER,
            'fileName' => 'maneater.pdf',
            'originalFileName' => 'maneater.pdf',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, ['acquisitionContract']);

            $contractFile = new AcquisitionContractFile();
            $this->merge($row, $contractFile);

            $manager->persist($contractFile);
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
