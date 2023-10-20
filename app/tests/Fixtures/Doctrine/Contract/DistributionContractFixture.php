<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\DistributionContract;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use App\Tests\Fixtures\Doctrine\CompanyFixture;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DistributionContractFixture extends AbstractFixture implements DependentFixtureInterface
{
    final public const WINNIE_THE_POOH = 'distribution_contract.winnie_the_pooh';
    final public const SNIPER_AND_MANEATER = 'distribution_contract.sniper_and_maneater';

    private const ROWS = [
        self::WINNIE_THE_POOH => [
            'name' => 'MW - Winnie the Pooh',
            'company' => CompanyFixture::HAUSSMANN_MEDIAS,
            'distributor' => CompanyFixture::HKA_FILMS,
            'type' => DistributionContractTypeEnum::ONE_OFF,
            'reportFrequency' => FrequencyEnum::MONTHLY,
        ],
        self::SNIPER_AND_MANEATER => [
            'name' => 'MDC - Sniper and Maneater',
            'company' => CompanyFixture::CHROME_FILMS,
            'distributor' => CompanyFixture::MEDIAWAN,
            'type' => DistributionContractTypeEnum::RECURRING,
            'reportFrequency' => FrequencyEnum::YEARLY,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $this->denormalizeReferenceFields($row, ['company', 'distributor']);

            $distributionContract = new DistributionContract();
            $this->merge($row, $distributionContract);

            $manager->persist($distributionContract);
            $this->setReference($reference, $distributionContract);
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
