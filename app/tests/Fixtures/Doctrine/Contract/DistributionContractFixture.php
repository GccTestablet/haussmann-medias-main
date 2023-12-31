<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine\Contract;

use App\Entity\Contract\DistributionContract;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use App\Tests\Fixtures\Doctrine\CompanyFixture;
use App\Tests\Fixtures\Doctrine\Setting\BroadcastChannelFixture;
use App\Tests\Fixtures\Doctrine\Setting\TerritoryFixture;
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
            'distributor' => CompanyFixture::MEDIAWAN,
            'type' => DistributionContractTypeEnum::ONE_OFF,
            'signedAt' => '2021-10-01',
            'reportFrequency' => FrequencyEnum::MONTHLY,
            'territories' => [TerritoryFixture::FRANCE, TerritoryFixture::UNITED_KINGDOM],
            'broadcastChannels' => [BroadcastChannelFixture::AVOD, BroadcastChannelFixture::TVOD, BroadcastChannelFixture::SVOD],
        ],
        self::SNIPER_AND_MANEATER => [
            'name' => 'MDC - Sniper and Maneater',
            'company' => CompanyFixture::CHROME_FILMS,
            'distributor' => CompanyFixture::MY_DIGITAL_COMPANY,
            'type' => DistributionContractTypeEnum::RECURRING,
            'signedAt' => '2022-03-07',
            'reportFrequency' => FrequencyEnum::YEARLY,
            'territories' => [TerritoryFixture::UNITED_STATES, TerritoryFixture::UNITED_KINGDOM],
            'broadcastChannels' => [BroadcastChannelFixture::AVOD, BroadcastChannelFixture::TVOD, BroadcastChannelFixture::SVOD],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $this->denormalizeDateTimeFields($row, ['signedAt']);
            $this->denormalizeReferenceFields($row, ['company', 'distributor']);
            $this->denormalizeArrayCollectionReferenceFields($row, ['territories', 'broadcastChannels']);

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
            TerritoryFixture::class,
            BroadcastChannelFixture::class,
        ];
    }
}
