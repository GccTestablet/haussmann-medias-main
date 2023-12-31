<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\AcquisitionContract;
use App\Enum\Common\FrequencyEnum;
use App\Tests\Fixtures\Doctrine\CompanyFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AcquisitionContractFixture extends AbstractFixture implements DependentFixtureInterface
{
    final public const WINNIE_THE_POOH = 'acquisition_contract.winnie_the_pooh';
    final public const SNIPER_AND_MANEATER = 'acquisition_contract.sniper_and_maneater';
    final public const HURRICANE = 'acquisition_contract.hurricane';

    private const ROWS = [
        self::WINNIE_THE_POOH => [
            'name' => 'HF - Winnie the Pooh',
            'company' => CompanyFixture::HAUSSMANN_MEDIAS,
            'beneficiary' => CompanyFixture::HKA_FILMS,
            'signedAt' => '2023-01-01',
            'startsAt' => '2023-01-01',
            'endsAt' => '2023-12-31',
            'reportFrequency' => FrequencyEnum::MONTHLY,
        ],
        self::SNIPER_AND_MANEATER => [
            'name' => 'MW - Sniper and Maneater',
            'company' => CompanyFixture::CHROME_FILMS,
            'beneficiary' => CompanyFixture::MEDIAWAN,
            'signedAt' => '2023-01-01',
            'startsAt' => '2023-01-01',
            'reportFrequency' => FrequencyEnum::YEARLY,
        ],
        self::HURRICANE => [
            'name' => 'CF - Hurricane',
            'company' => CompanyFixture::HAUSSMANN_MEDIAS,
            'beneficiary' => CompanyFixture::CHROME_FILMS,
            'signedAt' => '2022-01-01',
            'startsAt' => '2022-04-12',
            'reportFrequency' => FrequencyEnum::MONTHLY,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $this->denormalizeDateTimeFields($row, ['signedAt', 'startsAt', 'endsAt']);
            $this->denormalizeReferenceFields($row, ['company', 'beneficiary']);

            $acquisitionContract = new AcquisitionContract();
            $this->merge($row, $acquisitionContract);

            $manager->persist($acquisitionContract);
            $this->setReference($reference, $acquisitionContract);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CompanyFixture::class,
        ];
    }
}
